const Router = require("koa-router");
const path = require("path");
const fs = require("fs").promises;
const db = require("../../../models");
const bcrypt = require("bcrypt");
const crypto = require("crypto");
const { pick, sendEmail } = require("../../utils");
const passport = require("koa-passport");
const { gte, lte } = require("sequelize").Op;

const routes = new Router({
  prefix: "/user",
});

routes.get("/", async (ctx) => {
  await ctx.render("frontend/user/profile", { title: "Edit user profile" });
});

routes.get("/register", async (ctx) => {
  await ctx.render("frontend/user/register", { title: "User registration" });
});

routes.post("/store", async (ctx) => {
  const v = ctx.validator(ctx.request.body, {
    first_name: "required|maxLength:10|minLength:2",
    last_name: "required|maxLength:10|minLength:2",
    email: "required|email",
    password: "required|maxLength:18|minLength:8",
    confirm: "required|maxLength:18|minLength:8|same:password",
  });

  let keys = Object.keys(ctx.request.body);
  let oldInput = {};
  keys.map((i) => {
    oldInput[i] = { value: ctx.request.body[i] };
  });

  let exist = await db.User.count({
    where: {
      email: ctx.request.body.email,
    },
  });

  if ((await v.fails()) || exist) {
    console.log(v.errors);
    keys.map((i) => {
      if (i in v.errors) {
        oldInput[i] = { ...oldInput[i], message: v.errors[i].message };
      }
    });
    if (exist) {
      oldInput.email.message =
        "This email has been token, please change another one";
    }
    ctx.session.errors = {
      oldInput,
    };
    ctx.redirect("/user/register");
    return;
  }

  let user = pick(
    "first_name",
    "last_name",
    "email",
    "password"
  )(ctx.request.body);

  user.is_comfirmed = false;
  let salt = bcrypt.genSaltSync(10);
  user.password = bcrypt.hashSync(user.password, salt);
  try {
    await db.sequelize.transaction(async (t) => {
      let newUser = await db.User.create(user, { transaction: t });

      // Generate a email verification url
      let tomorrow = new Date();
      tomorrow.setDate(tomorrow.getDate() + 1);

      let code = crypto.randomBytes(30).toString("hex");
      await db.EmailVerification.create(
        {
          url: code,
          expired_at: tomorrow,
          UserId: newUser.id,
        },
        { transaction: t }
      );
      // Send eamil
      // http://localhost:8000/user/12/verify?code=9549182877eaad3718765dfbbed5709a04eb88656a926c7d60468c1f386b
      await sendEmail(
        ctx.request.body.email,
        "Recipe registration verification",
        `http://localhost:8000/user/${newUser.id}/verify?code=${code}`
      );
    });
  } catch (error) {
    // User register failed.
    console.log(error);
    ctx.redirect("/user/register");
    return;
  }

  ctx.redirect("/user/login");
});

routes.get("/:id/verify", async (ctx) => {
  if (ctx.query.code) {
    let code = ctx.query.code;
    let id = ctx.params.id;
    let exist = await db.EmailVerification.count({
      where: {
        url: code,
        userId: id,
        expired_at: {
          [gte]: new Date(),
        },
      },
    });
    if (exist) {
      try {
        db.sequelize.transaction(async (t) => {
          await db.User.update(
            {
              is_comfirmed: true,
            },
            {
              where: {
                id,
              },
              transaction: t,
            }
          );
          await db.EmailVerification.destroy({
            where: {
              url: code,
              userId: id,
            },
            transaction: t,
          });
        });
      } catch (error) {}
    }
  }
  ctx.redirect("/");
});

routes.get("/login", async (ctx) => {
  await ctx.render("frontend/user/login", { title: "User login" });
});

routes.post("/login", async (ctx) => {
  return passport.authenticate("local", (err, user, info, status) => {
    if (user) {
      ctx.login(user);
      ctx.redirect("/");
    } else {
      ctx.session.errors = {
        oldInput: {
          email: {
            value: ctx.request.body.email,
            message: "Email or password incorrect.",
          },
        },
      };
      ctx.redirect("/user/login");
    }
  })(ctx);
});

routes.post("/logout", async (ctx) => {
  if (ctx.isAuthenticated) {
    ctx.logout();
  }
  ctx.redirect("/");
});

routes.put("/:id/update", async (ctx) => {
  let v = ctx.validator(ctx.request.body, {
    first_name: "required|minLength:2|maxLength:18",
    last_name: "required|minLength:2|maxLength:18",
  });

  let keys = Object.keys(ctx.request.body);

  let oldInput = {};
  keys.map((i) => {
    oldInput[i] = { value: ctx.request.body[i] };
  });

  if (await v.fails()) {
    console.log(v.errors);
    keys.map((i) => {
      if (i in v.errors) {
        oldInput[i] = { ...oldInput[i], message: v.errors[i].message };
      }
    });
    ctx.session.errors = {
      oldInput,
    };
    ctx.redirect(`/user`);
    return;
  }

  ctx.state.user.first_name = ctx.request.body.first_name;
  ctx.state.user.last_name = ctx.request.body.last_name;

  let avatar = ctx.request.files.avatar;
  let image = "/uploads/" + path.basename(avatar.path);

  if (avatar.size == 0) {
    await fs.unlink(avatar.path);
  } else {
    ctx.state.user.avatar = image;
  }
  await ctx.state.user.save({ fields: ["avatar", "first_name", "last_name"] });
  ctx.redirect("/user");
});

routes.put("/:id/change", async (ctx) => {
  let v = ctx.validator(ctx.request.body, {
    old_password: "required|maxLength:18|minLength:8",
    password: "required|maxLength:18|minLength:8",
    confirm: "required|maxLength:18|minLength:8|same:password",
  });

  let keys = Object.keys(ctx.request.body);
  let oldInput = {};
  keys.map((i) => {
    oldInput[i] = { value: ctx.request.body[i] };
  });
  let match = bcrypt.compareSync(
    ctx.request.body.old_password,
    ctx.state.user.password
  );
  if ((await v.fails()) || !match) {
    keys.map((i) => {
      if (i in v.errors) {
        oldInput[i] = { ...oldInput[i], message: v.errors[i].message };
      }
    });
    if (!match) {
      oldInput.old_password.message = "Old password is worng.";
    }
    ctx.session.errors = {
      oldInput,
    };
    ctx.redirect("/user");
    return;
  }
  let salt = bcrypt.genSaltSync(10);
  let p = bcrypt.hashSync(ctx.request.body.password, salt);
  await ctx.state.user.update({ password: p });
  ctx.redirect("/user");
});

routes.get("/:id/recipes", async (ctx) => {
  await ctx.render("frontend/user/recipes", { title: "User's recipies" });
});

module.exports = routes;
