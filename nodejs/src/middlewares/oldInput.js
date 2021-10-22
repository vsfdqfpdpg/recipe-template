const oldInput = () => {
  return (ctx, next) => {
    if (!ctx.session) {
      return next();
    }
    ctx.state.oldInput = {};
    if (ctx.request.method === "POST") {
      ctx.state.oldInput = {};
    } else if (ctx.request.method === "GET") {
      if ("errors" in ctx.session && "oldInput" in ctx.session.errors) {
        ctx.state.oldInput = ctx.session.errors.oldInput;
        delete ctx.session.errors;
      }
    }
    return next();
  };
};

module.exports = oldInput;
