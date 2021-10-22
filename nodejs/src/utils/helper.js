const nodemailer = require("nodemailer");

const pick =
  (...props) =>
  (o) =>
    props.reduce((a, e) => ({ ...a, [e]: o[e] }), {});

async function sendEmail(to, subject, text) {
  let transporter = nodemailer.createTransport({
    service: "QQex",
    auth: {
      user: process.env.MAIL_USERNAME,
      pass: process.env.MAIL_PASSWORD,
    },
  });

  let info = await transporter.sendMail({
    from: process.env.MAIL_USERNAME,
    to,
    subject,
    text,
  });

  console.log("Message sent: %s", info.messageId);
}

const dateFormat = (d) =>
  [d.getMonth() + 1, d.getDate(), d.getFullYear()].join("/") +
  " " +
  [d.getHours(), d.getMinutes(), d.getSeconds()].join(":");

const getPaginationParams = (ctx) => {
  let limit = ctx.query.limit ? +ctx.query.limit : 20;
  let offset =
    ctx.query.page && ctx.query.page - 1 > 0 ? (ctx.query.page - 1) * limit : 0;
  let currentPage = ctx.query.page || 1;
  return { limit, offset, currentPage };
};

const getPagination = (ctx, obj) => {
  let { limit, currentPage } = getPaginationParams(ctx);
  let totalPage = Math.ceil(obj.count / limit);
  return {
    currentPage: parseInt(currentPage),
    totalPage,
    path: `${ctx.path}?limit=${limit}`,
  };
};

module.exports = {
  pick,
  sendEmail,
  dateFormat,
  getPaginationParams,
  getPagination,
};
