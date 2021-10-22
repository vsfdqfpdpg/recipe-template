"use strict";
const { Model, Op } = require("sequelize");
const moment = require("moment");
module.exports = (sequelize, DataTypes) => {
  class User extends Model {
    /**
     * Helper method for defining associations.
     * This method is not a part of Sequelize lifecycle.
     * The `models/index` file will call this method automatically.
     */
    static associate(models) {
      // define association here
      this.hasOne(models.EmailVerification);
      this.hasMany(models.Recipe);
      this.hasMany(models.Comment);

      this.belongsToMany(models.Role, { through: "UserRole" });

      this.belongsToMany(models.Role.scope("admin"), {
        through: "UserRole",
        as: "AdminRole",
      });
    }

    async isAdmin() {
      let roles = await this.getRoles({
        where: {
          title: "Admin",
        },
      });
      return roles.length != 0;
    }

    async hasAnyRoles(roles) {
      let hasRoles = await this.getRoles({
        where: {
          title: {
            [Op.in]: roles,
          },
        },
      });
      return hasRoles.length > 0;
    }

    async isAdminAlternative() {
      // by using scope
      return await this.countAdminRole();
    }
  }
  User.init(
    {
      first_name: DataTypes.STRING,
      last_name: DataTypes.STRING,
      name: {
        type: DataTypes.VIRTUAL,
        get() {
          return `${this.first_name} ${this.last_name}`;
        },
      },
      email: DataTypes.STRING,
      password: DataTypes.STRING,
      is_comfirmed: DataTypes.BOOLEAN,
      avatar: DataTypes.STRING,
      createdAt: {
        type: DataTypes.DATEONLY,
        get() {
          return moment(this.getDataValue("DateTime")).format(
            "YYYY-MM-DD HH:mm:ss"
          );
        },
      },
    },
    {
      sequelize,
      modelName: "User",
    }
  );
  return User;
};
