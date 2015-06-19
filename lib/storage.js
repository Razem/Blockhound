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
  var values = [];
  for (var actionName in actions) {
    values.push([date, uuid, world, pos[0], pos[1], pos[2], actionName, actions[actionName]]);
  }

  var query = conn.query(
    "INSERT INTO blockhound_actions(date_action, uuid, world_type, pos_x, pos_y, pos_z, action_name, action_value) VALUES ?",
    [values],
    function (err, res) {
      if (err) throw err;
    }
  );
};
