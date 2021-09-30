const Router = require("koa-router");
const user = require("./user");
const role = require("./role");
const permission = require("./permission");
const routes = new Router({
  prefix: "/admin",
});

routes.get("/", async (ctx) => {
  await ctx.render("admin/home/index", { title: "Admin areas" });
});

routes.use("", user.routes(), user.allowedMethods());
routes.use("", role.routes(), role.allowedMethods());
routes.use("", permission.routes(), permission.allowedMethods());

module.exports = routes;
