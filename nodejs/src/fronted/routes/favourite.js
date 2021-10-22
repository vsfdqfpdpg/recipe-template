const Router = require("koa-router");
const db = require("../../../models");

const routes = new Router({
  prefix: "/favourite",
});

routes.post("/:id", async (ctx) => {
  let type = ctx.request.body.type;
  let favourite = await db.Favourite.create({
    UserId: ctx.state.user.id,
    object_id: ctx.params.id,
    object_type: type[0].toUpperCase() + type.slice(1),
  });

  ctx.body = favourite;
});

routes.put("/:id", async (ctx) => {
  let type = ctx.request.body.type;
  let deleted = await db.Favourite.destroy({
    where: {
      UserId: ctx.state.user.id,
      object_id: ctx.params.id,
      object_type: type[0].toUpperCase() + type.slice(1),
    },
  });
  ctx.body = deleted;
});

module.exports = routes;
