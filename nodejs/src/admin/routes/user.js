const Router = require("koa-router");
const db = require("../../../models");
const { getPaginationParams, getPagination } = require("../../utils");

const routes = new Router({
  prefix: "/user",
});

routes.get("/", async (ctx) => {
  let { limit, offset } = getPaginationParams(ctx);
  let users = await db.User.findAndCountAll({
    order: [["updatedAt", "desc"]],
    limit,
    offset,
    distinct: true,
    include: [
      {
        model: db.Role,
        attributes: ["title"],
      },
    ],
  });

  await ctx.render("admin/user/list", {
    title: "Manage user",
    users,
    pagination: getPagination(ctx, users),
  });
});

routes.get("/:id/role", async (ctx) => {
  let id = ctx.params.id;
  let member = await db.User.findByPk(id, {
    include: [{ model: db.Role, attributes: ["id", "title"] }],
  });
  let roles = await db.Role.findAll({ attributes: ["id", "title"] });
  await ctx.render("admin/user/role", { title: "Assign role", member, roles });
});

routes.post("/:id/role", async (ctx) => {
  let id = ctx.params.id;
  let user = await db.User.findByPk(id, {
    include: [
      {
        model: db.Role,
        attributes: ["id"],
      },
    ],
  });

  let dbRole = user.Roles.map((r) => r.id + "");
  let reqRole = [...ctx.request.body.role];
  // check if has a role need to be removed from database
  let removed = dbRole.filter((d) => !reqRole.includes(d));
  console.log("removed", dbRole, reqRole, removed);
  if (removed.length) {
    await user.removeRoles(removed);
  }
  // check if has a role need to added to database
  let added = reqRole.filter((r) => !dbRole.includes(r));
  console.log("added", dbRole, reqRole, added);
  if (added.length) {
    await user.addRole(added);
  }
  ctx.redirect(`/admin/user/${id}/role`);
});

routes.get("/create", (ctx) => {});

routes.post("/store", (ctx) => {});

routes.get("/:id", (ctx) => {});

routes.get("/edit", (ctx) => {});

routes.put("/update", (ctx) => {});

routes.delete("/destroy", (ctx) => {});

module.exports = routes;
