'use strict';
var
config = require("../config.json"),
dirs = require("./dirs.json"),
FS = require("fs"),
Path = require("path");

// http://minecraft.gamepedia.com/Stats
// http://minecraft.gamepedia.com/Scoreboard

exports.load = function (uuid, callback) {
  FS.readFile(Path.join(config.worldPath, dirs.stats, uuid + ".json"), function (err, data) {
    if (err) throw err;

    data = JSON.parse(data.toString());

    var
    rgx = /^stat\.((mineBlock|useItem|killEntity)\.|(deaths|leaveGame|chestOpened|trappedChestTriggered|hopperInspected|dropperInspected|dispenserInspected|furnaceInteraction|brewingstandInteraction)$)/,
    res = {};

    for (var key in data) {
      if (rgx.test(key)) {
        res[key.slice(5).replace(/\.minecraft\./, ".")] = data[key];
      }
    }

    callback(res);
  });
};
