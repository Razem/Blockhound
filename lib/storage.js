'use strict';
var
config = require("../config.json"),
MySQL = require("mysql");

var conn = MySQL.createConnection({
  host: config.database.host,
  user: config.database.user,
  password: config.database.pass,
  database: config.database.db
});

conn.connect(function (err) {
  if (err) throw err;
});

exports.addActions = function (uuid, date, actions, world, pos) {
  var
  values = [],
  x = Math.round(pos[0] * 100) / 100,
  y = Math.round(pos[1] * 100) / 100,
  z = Math.round(pos[2] * 100) / 100;

  for (var actionName in actions) {
    values.push([date, uuid, world, x, y, z, actionName, actions[actionName]]);
  }

  var query = conn.query(
    "INSERT INTO blockhound_actions(date_action, uuid, world_type, pos_x, pos_y, pos_z, action_name, action_value) VALUES ?",
    [values],
    function (err, res) {
      if (err) throw err;
    }
  );
};
