"use strict";
const faker = require("faker");
const { Op } = require("sequelize");

module.exports = {
  up: async (queryInterface, Sequelize) => {
    /**
     * Add seed commands here.
     *
     * Example:
     */
    await queryInterface.bulkInsert(
      "Users",
      Array.from({ length: 30 }, () => {
        return {
          first_name: faker.name.findName(),
          last_name: faker.name.lastName(),
          email: faker.internet.email(),
          password: faker.internet.password(8),
          is_comfirmed: 0,
          createdAt: new Date(),
          updatedAt: new Date(),
        };
      }),
      {}
    );
  },

  down: async (queryInterface, Sequelize) => {
    /**
     * Add commands to revert seed here.
     *
     * Example:
     */
    await queryInterface.bulkDelete(
      "Users",
      {
        createdAt: {
          [Op.lt]: new Date(),
          [Op.gt]: new Date(new Date() - 24 * 60 * 60 * 1000),
        },
      },
      {}
    );
  },
};
