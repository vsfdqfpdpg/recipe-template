const Router = require("koa-router");
const user = require("./user");
const recipe = require("./recipe");
const favourite = require("./favourite");
const db = require("../../../models");
const { CookingStyle, Category, Status } = require("../../enums");
const { getPagination, getPaginationParams } = require("../../utils");

const routes = new Router();

// attributes: {
//   include: [
//     [
//       db.sequelize.literal(`(
//             select count(r.name) from recipes as r right join favourites as f on r.id = f.object_id group by r.name
//           )`),
//       "laughReactionsCount",
//     ],
//   ],
// },

routes.get("/", async (ctx) => {
  let { limit, offset } = getPaginationParams(ctx);

  let where = { object_type: "Recipe" };
  let whereComment = { object_type: "Comment" };

  if (ctx.isAuthenticated()) {
    where["UserId"] = ctx.state.user.id;
    whereComment["UserId"] = ctx.state.user.id;
  }

  let recipes = await db.Recipe.findAndCountAll({
    where: {
      status: Status.PASS,
    },
    order: [
      ["updatedAt", "DESC"],
      [db.Comment, "createdAt", "DESC"],
      [
        db.Comment,
        { model: db.Comment, as: "SubComment" },
        "createdAt",
        "DESC",
      ],
    ],
    distinct: true,
    limit: limit,
    offset: offset,
    // group: [
    //   "Recipe.id",
    //   "Recipe.name",
    //   "Recipe.preserve",
    //   "Recipe.cooking_style",
    //   "Recipe.category",
    //   "Recipe.image",
    //   "Recipe.description",
    //   "Recipe.duration",
    //   "Recipe.UserId",
    //   "Favourites.id",
    //   "Comments.id",
    //   "Comments->SubComment.id",
    // ],
    // attributes: {
    //   include: [
    //     [
    //       db.Sequelize.fn("COUNT", db.Sequelize.col("Favourites.id")),
    //       "sensorCount",
    //     ],
    //   ],
    // },
    include: [
      {
        model: db.User,
        attributes: ["id", "first_name", "last_name", "avatar"],
      },
      {
        model: db.Favourite,
        where,
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
            model: db.Favourite,
            where: whereComment,
            required: false,
          },
          {
            model: db.Comment,
            as: "SubComment",
            include: [
              db.User,
              {
                model: db.Favourite,
                where: whereComment,
                required: false,
              },
            ],
            where: {
              object_type: "Comment",
            },
            required: false,
          },
        ],
      },
    ],
  });
  await ctx.render("frontend/home/index", {
    title: "Recipes Project",
    recipes,
    pagination: getPagination(ctx, recipes),
    enums: {
      CookingStyle,
      Category,
      Status,
    },
  });
});

routes.use("/user", async (ctx, next) => {
  if (
    ctx.method != "GET" &&
    !ctx.isAuthenticated() &&
    ctx.url != "/user/login"
  ) {
    console.log(ctx.url);
    ctx.status = 401;
    ctx.redirect("/user/login");
    return;
  }
  return next();
});

routes.use(["/recipe", "/favourite"], async (ctx, next) => {
  let excludes = [
    /\/recipe\/create.*/,
    /recipe\/\w+\/delete.*/,
    /\/recipe\/\w+\/edit.*/,
  ];
  if (!ctx.isAuthenticated()) {
    if (
      ctx.method != "GET" ||
      (ctx.method == "GET" && excludes.some((re) => re.test(ctx.url)))
    ) {
      console.log(ctx.url);
      ctx.status = 401;
      ctx.redirect("/user/login");
      return;
    } else {
      return next();
    }
  }
  return next();
});

routes.use("", user.routes(), user.allowedMethods());
routes.use("", recipe.routes(), recipe.allowedMethods());
routes.use("", favourite.routes(), favourite.allowedMethods());

module.exports = routes;
