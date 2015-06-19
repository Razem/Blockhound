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

// Watching for changes
var watcher;
function startWatcher() {
//  var last = Date.now();

  watcher = FS.watch(Path.join(config.worldPath, dirs.stats), function (event, fileName) {
    if (!fileName || !jsonRgx.test(fileName)) return;

    var uuid = fileName.replace(jsonRgx, "");
//    var now = Date.now();
//    console.log(now, now - last, event, uuid);
//
//    last = now;

    update(uuid);
  });
}

exports.stopWatcher = function () {
  if (watcher) {
    watcher.close();
  }
};

// Update
var updating = {};
function update(uuid) {
  if (updating[uuid]) return;

  updating[uuid] = true;

  var count = 2, data, player, date = new Date();

  setTimeout(function () {
    Stats.load(uuid, function (_data) {
      data = _data;

      saveDiffs();
    });

    Player.load(uuid, function (_player) {
      player = _player;

      saveDiffs();
    });
  }, 2000);

  function saveDiffs() {
    if (--count !== 0) return;

    updating[uuid] = false;

    var diff = compare(uuid, data);

    console.log(+date, uuid, JSON.stringify(diff), player.Dimension, player.Pos);

    state[uuid] = data;
  }
}

function compare(uuid, data) {
  var old = state[uuid];
  if (!old) return data;

  var res = {};
  for (var key in data) {
    var
    oldItem = old[key],
    item = data[key],
    diff = oldItem ? item - oldItem : item;

    if (diff !== 0) {
      res[key] = diff;
    }
  }

  return res;
}
