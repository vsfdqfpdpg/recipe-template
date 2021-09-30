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
module.exports = {
  pick,
  sendEmail,
};
