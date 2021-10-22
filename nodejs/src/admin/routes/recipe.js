const Router = require("koa-router");
const db = require("../../../models");
const { CookingStyle, Category, Status } = require("../../enums");
const { Op } = require("sequelize");
const { getPaginationParams, getPagination } = require("../../utils");

const routes = new Router({
  prefix: "/recipe",
});

routes.get("/", async (ctx) => {
  let { limit, offset } = getPaginationParams(ctx);
  let recipes = await db.Recipe.findAndCountAll({
    order: [["updatedAt", "desc"]],
    where: {
      status: {
        [Op.in]: [Status.REJECTED, Status.PENDING],
      },
    },
    limit,
    offset,
    include: [
      {
        model: db.User,
        attributes: ["first_name", "last_name"],
      },
    ],
  });

  await ctx.render("admin/recipes/list", {
    title: "List recipes",
    recipes,
    pagination: getPagination(ctx, recipes),
    enum: { CookingStyle, Category, Status },
  });
});

module.exports = routes;
