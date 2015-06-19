'use strict';
var
config = require("../config.json"),
dirs = require("./dirs.json"),
FS = require("fs"),
Path = require("path"),
Stats = require("./stats"),
Player = require("./player");

var state = {}, jsonRgx = /\.json$/;

exports.init = function () {
  var
  files = FS.readdirSync(Path.join(config.worldPath, dirs.stats)),
  count = files.length;

  for (var i = 0; i < files.length; ++i) {
    if (!jsonRgx.test(files[i])) continue;

    var uuid = files[i].replace(jsonRgx, "");

    Stats.load(uuid, function (uuid, data) {
      state[uuid] = data;

      if (--count === 0) {
        startWatcher();
      }
    }.bind(null, uuid));
  }
};

var watcher;
function startWatcher() {
  var last = Date.now();

  watcher = FS.watch(Path.join(config.worldPath, dirs.stats), function (event, fileName) {
    if (!fileName || !jsonRgx.test(fileName)) return;

    var uuid = fileName.replace(jsonRgx, "");
    var now = Date.now();
    console.log(now, now - last, event, uuid);

    last = now;
  });
}

exports.stopWatcher = function () {
  if (watcher) {
    watcher.close();
  }
};
