const Router = require("koa-router");
const user = require("./user");
const recipe = require("./recipe");

const routes = new Router();

routes.get("/", async (ctx) => {
  await ctx.render("frontend/home/index", { title: "Recipes Project" });
});

routes.use("", user.routes(), user.allowedMethods());
routes.use("", recipe.routes(), recipe.allowedMethods());

module.exports = routes;
