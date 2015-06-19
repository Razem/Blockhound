'use strict';
var
config = require("./config.json"),
FS = require("fs"),
Path = require("path"),
Stats = require("./lib/stats"),
Player = require("./lib/player");

var uuid = "07d08008-e6d7-44c7-9fa6-32c75e2888e9";

Player.load(uuid, function (player) {
  console.log(player.Dimension, player.Pos);
});

Stats.load(uuid, function (data) {
  for (var key in data) {
    console.log(key, data[key]);
  }
});

var watcher = FS.watch("./_test", function (event, fileName) {
  console.log(Date.now(), event, fileName);
});

Player.getUUID("razemix", function (uuid) {
  console.log(uuid);

  Player.getName(uuid, function (name) {
    console.log(name);
  });
});
