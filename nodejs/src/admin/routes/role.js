const Router = require("koa-router");
const db = require("../../../models");
const { pick } = require("../../utils");
const { getPagination, getPaginationParams } = require("../../utils");

const routes = new Router({
  prefix: "/role",
});

routes.get("/", async (ctx) => {
  let { limit, offset } = getPaginationParams(ctx);
  let roles = await db.Role.findAndCountAll({ limit, offset });
  await ctx.render("admin/role/list", {
    title: "Manage role",
    roles,
    pagination: getPagination(ctx, roles),
  });
});

routes.get("/create", async (ctx) => {
  await ctx.render("admin/role/create", { title: "Create a role" });
});

routes.post("/store", async (ctx) => {
  if ("active" in ctx.request.body == false) {
    ctx.request.body.active = false;
  } else {
    ctx.request.body.active = true;
  }
  await db.Role.create(ctx.request.body);
  ctx.redirect("/admin/role");
});

routes.get("/:id", async (ctx) => {
  let role = await db.Role.findByPk(ctx.params.id, {
    include: [
      {
        model: db.Permission,
        attributes: ["id", "title", "description"],
      },
    ],
  });
  await ctx.render("admin/role/show", {
    title: "Show role's permission",
    role,
  });
});

routes.get("/:id/edit", async (ctx) => {
  let role = await db.Role.findByPk(ctx.params.id);
  await ctx.render("admin/role/edit", { title: "Edit a role", role });
});

routes.put("/:id/update", async (ctx) => {
  let role = pick("title", "description", "active")(ctx.request.body);
  role.active = role.active ? true : false;
  await db.Role.update(role, {
    where: {
      id: ctx.params.id,
    },
  });
  ctx.redirect("/admin/role");
});

routes.get("/:id/delete", async (ctx) => {
  let role = await db.Role.findByPk(ctx.params.id);
  await ctx.render("admin/role/delete", { title: "Delete a role", role });
});

routes.delete("/:id/destroy", async (ctx) => {
  await db.Role.destroy({
    where: {
      id: ctx.params.id,
    },
  });
  ctx.redirect("/admin/role");
});

routes.get("/:id/assign", async (ctx) => {
  let role = await db.Role.findByPk(ctx.params.id, {
    include: db.Permission,
  });

  let permissions = await db.Permission.findAll({
    where: { active: true },
    attributes: ["id", "title"],
  });
  await ctx.render("admin/role/assign", {
    title: "Assign a permission",
    role,
    permissions,
  });
});

routes.post("/:id/assign", async (ctx) => {
  let role = await db.Role.findByPk(ctx.params.id, {
    include: db.Permission,
  });

  let did = role.Permissions.map((i) => i.id + "");

  if ("permissions" in ctx.request.body) {
    let pid = [...ctx.request.body.permissions];

    // Delete ids not in request
    let removePids = did.filter((i) => !pid.includes(i));
    if (removePids.length) {
      await role.removePermissions(removePids);
    }
    // Add ids not in database
    let addPids = pid.filter((i) => !did.includes(i));
    if (addPids.length) {
      await role.addPermissions(addPids);
    }
  } else {
    if (did.length) {
      await role.removePermissions(did);
    }
  }
  ctx.redirect("/admin/role");
});
module.exports = routes;
