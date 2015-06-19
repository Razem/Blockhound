'use strict';
var
config = require("../config.json"),
dirs = require("./dirs.json"),
FS = require("fs"),
Path = require("path");

// http://minecraft.gamepedia.com/Stats

exports.load = function (uuid, callback) {
  FS.readFile(Path.join(config.worldPath, dirs.stats, uuid + ".json"), function (err, data) {
    if (err) throw err;

    data = JSON.parse(data.toString());

    var
    rgx = /^stat\.(mineBlock\.|useItem\.|(deaths|chestOpened|trappedChestTriggered|hopperInspected|dropperInspected|dispenserInspected|furnaceInteraction|brewingstandInteraction|mobKills)$)/,
    res = {};

    for (var key in data) {
      if (rgx.test(key)) {
        res[key.slice(5).replace(/\.minecraft\./, ".")] = data[key];
      }
    }

    callback(res);
  });
};
