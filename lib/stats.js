'use strict';
var
config = require("../config.json"),
dirs = require("./dirs.json"),
FS = require("fs"),
Path = require("path");

exports.load = function (uuid, callback) {
  FS.readFile(Path.join(config.worldPath, dirs.stats, uuid + ".json"), function (err, data) {
    if (err) throw err;

    data = JSON.parse(data.toString());

    var rgx = /^stat\.(mineBlock\.|useItem\.minecraft\.flint_and_steel$)/, res = {};
    for (var key in data) {
      if (rgx.test(key)) {
        res[key] = data[key];
      }
    }

    callback(res);
  });
};
