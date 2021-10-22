"use strict";
const { Model } = require("sequelize");
module.exports = (sequelize, DataTypes) => {
  class Recipe extends Model {
    /**
     * Helper method for defining associations.
     * This method is not a part of Sequelize lifecycle.
     * The `models/index` file will call this method automatically.
     */
    static associate(models) {
      // define association here
      this.belongsTo(models.User);
      this.hasMany(models.Comment, {
        foreignKey: "object_id",
      });
      this.hasMany(models.Favourite, {
        foreignKey: "object_id",
      });
    }
  }
  Recipe.init(
    {
      name: DataTypes.STRING,
      preserve: DataTypes.BOOLEAN,
      cooking_style: DataTypes.STRING,
      category: DataTypes.STRING,
      image: DataTypes.STRING,
      description: DataTypes.TEXT,
      duration: DataTypes.INTEGER,
      status: DataTypes.STRING,
      UserId: DataTypes.INTEGER,
    },
    {
      sequelize,
      modelName: "Recipe",
    }
  );

  Recipe.addHook("beforeDestroy", async (instance, options) => {
    console.log(Reflect.ownKeys(instance.__proto__));
    let favourites = await instance.getFavourites();
    Promise.all(favourites.map((favourite) => favourite.destroy()));
  });

  return Recipe;
};
