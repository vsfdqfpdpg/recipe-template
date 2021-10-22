const Router = require("koa-router");
const { getPagination, getPaginationParams } = require("../../utils");
const db = require("../../../models");

const routes = new Router({
  prefix: "/permission",
});

routes.get("/", async (ctx) => {
  let { limit, offset } = getPaginationParams(ctx);
  let permissions = await db.Permission.findAndCountAll({ limit, offset });
  await ctx.render("admin/permission/list", {
    title: "Manage permission",
    permissions,
    pagination: getPagination(ctx, permissions),
  });
});

routes.get("/create", async (ctx) => {
  await ctx.render("admin/permission/create", { title: "Create a permission" });
});

routes.post("/store", async (ctx) => {
  if ("active" in ctx.request.body == false) {
    ctx.request.body.active = false;
  } else {
    ctx.request.body.active = true;
  }
  await db.Permission.create(ctx.request.body);
  ctx.redirect("/admin/permission");
});

routes.get("/:id", (ctx) => {});

routes.get("/:id/edit", async (ctx) => {
  let id = ctx.params.id;
  let permission = await db.Permission.findByPk(id);
  await ctx.render("admin/permission/edit", {
    title: "Edit permission",
    permission,
  });
});

routes.put("/:id/update", async (ctx) => {
  const id = ctx.params.id;
  const pick =
    (...props) =>
    (o) =>
      props.reduce((a, e) => ({ ...a, [e]: o[e] }), {});
  let permission = pick("title", "description", "active")(ctx.request.body);
  permission.active = permission.active ? true : false;
  let result = await db.Permission.update(permission, {
    where: {
      id,
    },
  });
  ctx.redirect("/admin/permission");
});

routes.get("/:id/delete", async (ctx) => {
  let id = ctx.params.id;
  let permission = await db.Permission.findByPk(id);

  await ctx.render("admin/permission/delete", {
    title: "Delete a permission",
    permission: permission,
  });
});

routes.delete("/:id/destroy", async (ctx) => {
  await db.Permission.destroy({
    where: {
      id: ctx.params.id,
    },
  });
  ctx.redirect("/admin/permission");
});

module.exports = routes;
