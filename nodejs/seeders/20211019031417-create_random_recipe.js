"use strict";
const { Op } = require("sequelize");
module.exports = {
  up: async (queryInterface, Sequelize) => {
    /**
     * Add seed commands here.
     *
     * Example:
     */
    await queryInterface.bulkInsert(
      "Recipes",
      Array.from({ length: 40 }, () => {
        return {
          name: "Bogus Gateway" + Math.floor(Math.random() * 7),
          preserve: false,
          cooking_style: Math.floor(Math.random() * 7),
          category: "0,1,2",
          image: "/uploads/77c0b0a0bc9c0d7e5e589cca06dd20ed.jpeg",
          description: `Tangy, creamy, and with a hint of spice, you can't beat pimento cheese when it comes to classic Southern side dishes. Serve it with crackers for dipping, or smear on toast points for a fancier presentation.
        Southern picnic side dishes. From creamy slaw to crunchy cucumber salad, we've got all your cravings covered.
        Tangy, creamy, and with a hint of spice, you can't beat pimento cheese when it comes to classic Southern side dishes. Serve it with crackers for dipping, or smear on toast points for a fancier presentation.
        No fish are required for this beloved Texan dish. Black and pinto beans, along with corn, make up the base. Each bite is seasoned with peppers, cilantro, and a zippy sweet and sour dressing. This makes a great picnic dish in the South because there's no mayo to keep cool.
        Irresistibly crunchy, deep-fried peanuts are the salty side dish that you didn't know you needed. "Passed down through generations, this is sure crowd pleaser," says Kristi Whittington.`,
          duration: Math.floor(Math.random() * (100 - 20) + 20),
          status: 0,
          UserId: 18,
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
      "Recipes",
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
