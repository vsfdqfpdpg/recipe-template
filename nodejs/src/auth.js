const passport = require("koa-passport");
const LocalStrategy = require("passport-local").Strategy;
const bcrypt = require("bcrypt");
const db = require("../models");

passport.serializeUser((user, done) => {
  console.log("passport.serializeUser", user);
  done(null, user.id);
});

passport.deserializeUser((id, done) => {
  console.log("passport.deserializeUser", id);
  return db.User.findByPk(id)
    .then((user) => {
      done(null, user);
    })
    .catch((err) => {
      done(err, null);
    });
});

passport.use(
  new LocalStrategy(
    { usernameField: "email", passwordField: "password" },
    async (username, password, done) => {
      const user = await db.User.findOne({ where: { email: username } });
      if (user && bcrypt.compareSync(password, user.password)) {
        done(null, user);
      } else {
        done(null, false);
      }
    }
  )
);
