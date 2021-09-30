const Router = require("koa-router");
const db = require("../../../models");
const fs = require("fs").promises;
const path = require("path");
const { CookingStyle, Category, Status } = require("../../enums");
const routes = new Router({
  prefix: "/recipe",
});

routes.get("/", async (ctx) => {
  let recipes = await db.Recipe.findAndCountAll({
    where: {
      UserId: ctx.state.user.id,
    },
  });
  await ctx.render("frontend/recipe/list", {
    title: "Recipes",
    recipes,
    enums: {
      CookingStyle,
      Category,
      Status,
    },
  });
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

  let recipe = await db.Recipe.create({
    name: ctx.request.body.name,
    preserve: ctx.request.body.preserve === "true",
    cooking_style: ctx.request.body.cooking_style,
    category: Array.isArray(category) ? category.join(",") : category,
    image: imagePath,
    description: ctx.request.body.description,
    duration: ctx.request.body.duration,
    UserId: ctx.state.user.id,
  });

  ctx.redirect("/recipe");
});

module.exports = routes;
