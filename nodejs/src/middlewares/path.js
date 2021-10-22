const url = () => {
  return (ctx, next) => {
    ctx.state.url = ctx.url;
    return next();
  };
};

module.exports = url;
