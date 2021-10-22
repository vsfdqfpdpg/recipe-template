"use strict";
const { Model } = require("sequelize");
module.exports = (sequelize, DataTypes) => {
  class Favourite extends Model {
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
      this.belongsTo(models.Comment, {
        as: "CommentId",
        foreignKey: "object_id",
      });
    }
  }
  Favourite.init(
    {
      UserId: DataTypes.INTEGER,
      object_id: DataTypes.INTEGER,
      object_type: DataTypes.STRING,
    },
    {
      sequelize,
      modelName: "Favourite",
    }
  );
  return Favourite;
};
