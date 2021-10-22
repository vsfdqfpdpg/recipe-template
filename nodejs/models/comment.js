"use strict";
const { Model } = require("sequelize");

module.exports = (sequelize, DataTypes) => {
  class Comment extends Model {
    /**
     * Helper method for defining associations.
     * This method is not a part of Sequelize lifecycle.
     * The `models/index` file will call this method automatically.
     */
    static associate(models) {
      // define association here
      this.belongsTo(models.User);
      this.belongsTo(models.Recipe, {
        as: "RecipeId",
        foreignKey: "object_id",
      });
      this.hasMany(models.Comment, {
        as: "SubComment",
        foreignKey: "object_id",
      });
      this.hasMany(models.Favourite, {
        foreignKey: "object_id",
      });
    }
  }
  Comment.init(
    {
      userId: DataTypes.INTEGER,
      object_id: DataTypes.INTEGER,
      object_type: DataTypes.STRING,
      comment: DataTypes.STRING,
    },
    {
      sequelize,
      modelName: "Comment",
    }
  );
  Comment.addHook("beforeDestroy", async (instance, options) => {
    let favourites = await instance.getFavourites();
    Promise.all(favourites.map((favourite) => favourite.destroy()));

    if ("Recipe" == instance.get("object_type")) {
      console.log(Reflect.ownKeys(instance.__proto__));
      let deleted = await Comment.destroy({
        where: {
          object_id: instance.id,
          object_type: "Comment",
        },
        individualHooks: true,
      });
    }
  });
  return Comment;
};
