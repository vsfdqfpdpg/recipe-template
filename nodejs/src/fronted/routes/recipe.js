const Router = require("koa-router");
const db = require("../../../models");
const fs = require("fs").promises;
const path = require("path");
const { CookingStyle, Category, Status } = require("../../enums");

const routes = new Router({
  prefix: "/recipe",
});

routes.get("/create", async (ctx) => {
  await ctx.render("frontend/recipe/create", {
    title: "Create a recipe",
    enums: { CookingStyle, Category },
  });
});

routes.post("/store", async (ctx) => {
  let image = ctx.request.files.image;
  let imagePath = "";
  if (image.size == 0) {
    await fs.unlink(image.path);
  } else {
    imagePath = "/uploads/" + path.basename(image.path);
  }

  let category = ctx.request.body.category;

  await db.Recipe.create({
    name: ctx.request.body.name,
    preserve: ctx.request.body.preserve === "true",
    cooking_style: ctx.request.body.cooking_style,
    category: Array.isArray(category) ? category.join(",") : category,
    image: imagePath,
    description: ctx.request.body.description,
    duration: ctx.request.body.duration,
    UserId: ctx.state.user.id,
  });

  ctx.redirect(`/user/${ctx.state.user.id}/recipes`);
});

let hasPermission = async (ctx, next) => {
  let recipe = await db.Recipe.findByPk(ctx.params.id);

  if (recipe.UserId != ctx.state.user.id) {
    ctx.status = 401;
    await ctx.render("frontend/errors/404", {
      title: "Error",
      message: "Don't have permission to edit this recipe.",
    });
    return;
  }
  ctx.recipe = recipe;
  return next();
};

routes.get("/:id/edit", hasPermission, async (ctx) => {
  await ctx.render("frontend/recipe/edit", {
    title: "Edit a recipe",
    recipe: ctx.recipe,
    enums: { CookingStyle, Category, Status },
  });
});

routes.put("/:id/update", hasPermission, async (ctx) => {
  let recipe = ctx.recipe;
  const { name, preserve, cooking_style, description, duration, category } =
    ctx.request.body;
  recipe.name = name;
  recipe.preserve = preserve;
  recipe.cooking_style = cooking_style;
  recipe.description = description;
  recipe.duration = duration;
  recipe.category = Array.isArray(category) ? category.join(",") : category;
  let image = ctx.request.files.image;
  if (image.size != 0) {
    recipe.image = "/uploads/" + path.basename(image.path);
  } else {
    await fs.unlink(image.path);
  }
  await recipe.save({
    fields: [
      "image",
      "name",
      "preserve",
      "cooking_style",
      "description",
      "duration",
      "category",
    ],
  });
  ctx.redirect(`/user/${ctx.state.user.id}/recipes`);
});

routes.get("/:id/delete", hasPermission, async (ctx) => {
  await ctx.render("frontend/recipe/delete", {
    title: "Delete a recipe",
    recipe: ctx.recipe,
  });
});

routes.delete("/:id/destroy", async (ctx) => {
  await db.Recipe.destroy({
    where: {
      id: ctx.params.id,
      UserId: ctx.state.user.id,
    },
    individualHooks: true,
  });
  ctx.redirect(`/user/${ctx.state.user.id}/recipes`);
});

routes.post("/:id/comment", async (ctx) => {
  let v = ctx.validator(ctx.request.body, {
    object_id: "required",
    comment: "required",
  });
  if (await v.fails()) {
    ctx.status = 401;
    ctx.body = v.errors;
    return;
  }
  let comment = {
    userId: ctx.state.user.id,
    object_id: ctx.params.id,
    object_type: ctx.request.body.type,
    comment: ctx.request.body.comment,
  };
  comment = await db.Comment.create(comment);
  ctx.body = { comment, user: ctx.state.user };
});

routes.delete("/:id/comment", async (ctx) => {
  await db.Comment.destroy({
    where: {
      id: ctx.params.id,
      UserId: ctx.state.user.id,
    },
    individualHooks: true,
  });
  ctx.body = ctx.params.id;
});

routes.get("/:id", async (ctx) => {
  let { id } = ctx.params;
  let canVerify = false;
  let recipe;
  if (ctx.isAuthenticated()) {
    canVerify = await ctx.state.user.hasAnyRoles(["Admin", "Editor"]);
    recipe = await db.Recipe.findByPk(id, {
      include: [
        {
          model: db.User,
          attributes: ["id", "avatar", "first_name", "last_name"],
        },
        {
          model: db.Favourite,
          where: {
            object_type: "Recipe",
            UserId: ctx.state.user.id,
          },
          required: false,
        },
        {
          model: db.Comment,
          where: {
            object_type: "Recipe",
          },
          required: false,
          include: [
            db.User,
            {
              model: db.Comment,
              as: "SubComment",
              include: [
                db.User,
                {
                  model: db.Favourite,
                  where: {
                    object_type: "Comment",
                    UserId: ctx.state.user.id,
                  },
                  required: false,
                },
              ],
              where: {
                object_type: "Comment",
              },
              required: false,
            },
            {
              model: db.Favourite,
              where: {
                object_type: "Comment",
                UserId: ctx.state.user.id,
              },
              required: false,
            },
          ],
        },
      ],
    });
  } else {
    recipe = await db.Recipe.findByPk(id, {
      include: [
        {
          model: db.User,
          attributes: ["id", "avatar", "first_name", "last_name"],
        },
        {
          model: db.Favourite,
          where: {
            object_type: "Recipe",
          },
          required: false,
        },
        {
          model: db.Comment,
          where: {
            object_type: "Recipe",
          },
          required: false,
          include: [
            db.User,
            {
              model: db.Comment,
              as: "SubComment",
              include: [
                db.User,
                {
                  model: db.Favourite,
                  where: {
                    object_type: "Comment",
                  },
                  required: false,
                },
              ],
              where: {
                object_type: "Comment",
              },
              required: false,
            },
            {
              model: db.Favourite,
              where: {
                object_type: "Comment",
              },
              required: false,
            },
          ],
        },
      ],
    });
  }

  if (!recipe || (recipe.status == Status.REJECTED && !ctx.isAuthenticated)) {
    ctx.status = 404;
    await ctx.render("frontend/errors/404", {
      title: "Recipe is not found.",
      message: `Recipe ${id} is not found.`,
    });
    return;
  }

  await ctx.render("frontend/recipe/view", {
    title: "View a recipe",
    recipe,
    enums: { CookingStyle, Category, Status },
    canVerify,
  });
});

const canEdit = async (ctx, next) => {
  if (ctx.isAuthenticated()) {
    if (await ctx.state.user.hasAnyRoles(["Admin", "Editor"])) {
      return next();
    } else {
      ctx.status = 403;
      ctx.throw(403, "You do not have permission to visit this resource.");
    }
  } else {
    return ctx.redirect("/user/login");
  }
};

routes.post("/:id/publish", canEdit, async (ctx) => {
  let recipe = await db.Recipe.findByPk(ctx.params.id);
  await recipe.update({ status: "1" });
  ctx.body = recipe;
});

routes.delete("/:id/publish", canEdit, async (ctx) => {
  let recipe = await db.Recipe.findByPk(ctx.params.id);
  await recipe.update({ status: "0" });
  ctx.body = recipe;
});

routes.post("/:id/reject", canEdit, async (ctx) => {
  let recipe = await db.Recipe.findByPk(ctx.params.id);
  await recipe.update({ status: "2" });
  ctx.body = recipe;
});

routes.delete("/:id/reject", canEdit, async (ctx) => {
  let recipe = await db.Recipe.findByPk(ctx.params.id);
  await recipe.update({ status: "0" });
  ctx.body = recipe;
});

module.exports = routes;
