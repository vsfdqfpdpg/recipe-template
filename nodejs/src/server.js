// https://www.codegrepper.com/code-examples/javascript/frameworks/nodejs/how+to+generate+random+string+in+node+js
const path = require("path");
const Koa = require("koa");
const dotevn = require("dotenv");
const override = require("koa-override");
const koaBody = require("koa-body");
const views = require("koa-views");
const session = require("koa-session");
const serve = require("koa-static");
const passport = require("koa-passport");
const niv = require("node-input-validator");
const adminRoutes = require("./admin/routes");
const frontedRoutes = require("./fronted/routes");
const { oldInput } = require("./middlewares");

dotevn.config();
const app = new Koa();
app.use(serve(path.join(path.dirname(__dirname), "static")));
app.keys = ["super-secret-key"];

app.use(session({}, app));

app.use(
  koaBody({
    formidable: {
      keepExtensions: true,
      uploadDir: path.join(path.dirname(__dirname), "static/uploads"),
      hash: "md5",
      onFileBegin: (name, file) => {
        file.path = file.path.replace("uploads/upload_", "uploads/");
      },
    },
    multipart: true,
  })
);

app.use(override());
app.use(niv.koa());
app.use(
  views(path.join(path.dirname(__dirname), "./src/views"), { extension: "ejs" })
);

require("./auth");
app.use(passport.initialize());
app.use(passport.session());

app.use(oldInput());
app.use(frontedRoutes.routes());
app.use(frontedRoutes.allowedMethods());
app.use(adminRoutes.routes());
app.use(adminRoutes.allowedMethods());

console.log(frontedRoutes.stack.map((i) => i.path));
console.log(adminRoutes.stack.map((i) => i.path));

app.listen(8000, () => {
  console.log("http://localhost:8000");
});
