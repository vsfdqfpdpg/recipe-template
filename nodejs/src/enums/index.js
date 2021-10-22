let CookingStyle;
(function (CookingStyle) {
  CookingStyle[(CookingStyle["BROILING"] = 0)] = "BROILING";
  CookingStyle[(CookingStyle["GRILLING"] = 1)] = "GRILLING";
  CookingStyle[(CookingStyle["ROASTING"] = 2)] = "ROASTING";
  CookingStyle[(CookingStyle["BAKING"] = 3)] = "BAKING";
  CookingStyle[(CookingStyle["SAUTEING"] = 4)] = "SAUTEING";
  CookingStyle[(CookingStyle["POACHING"] = 5)] = "POACHING";
  CookingStyle[(CookingStyle["SIMMERING"] = 6)] = "SIMMERING";
  CookingStyle[(CookingStyle["BOILING"] = 7)] = "BOILING";
})(CookingStyle || (CookingStyle = {}));

let Category;
(function (Category) {
  Category[(Category["BREAKFAST"] = 0)] = "BREAKFAST";
  Category[(Category["LUNCH"] = 1)] = "LUNCH";
  Category[(Category["DINNER"] = 2)] = "DINNER";
})(Category || (Category = {}));

let Status;
(function (Status) {
  Status[(Status["PENDING"] = 0)] = "PENDING";
  Status[(Status["PASS"] = 1)] = "PASS";
  Status[(Status["REJECTED"] = 2)] = "REJECTED";
})(Status || (Status = {}));

module.exports = {
  CookingStyle,
  Category,
  Status,
};
