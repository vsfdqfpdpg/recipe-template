const Router = require("koa-router");
const user = require("./user");
const role = require("./role");
const permission = require("./permission");
const recipe = require("./recipe");
const { Op } = require("sequelize");
const db = require("../../../models");

const routes = new Router({
  prefix: "/admin",
});

routes.use(async (ctx, next) => {
  if (ctx.isAuthenticated()) {
    if (await ctx.state.user.isAdmin()) {
      return next();
    } else {
      ctx.status = 403;
      ctx.throw(403, "You do not have permission to visit this resource.");
    }
  } else {
    return ctx.redirect("/user/login");
  }
});

routes.get("/", async (ctx) => {
  await ctx.render("admin/home/index", { title: "Admin areas" });
});

routes.use("", user.routes(), user.allowedMethods());
routes.use("", role.routes(), role.allowedMethods());
routes.use("", permission.routes(), permission.allowedMethods());
routes.use("", recipe.routes(), recipe.allowedMethods());

module.exports = routes;
