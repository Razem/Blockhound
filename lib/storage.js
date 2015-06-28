'use strict';
var
config = require("../config.json"),
MySQL = require("mysql");

var
dbConfig = {
  host: config.database.host,
  user: config.database.user,
  password: config.database.pass,
  database: config.database.db
},
conn;

function establishConnection() {
  conn = MySQL.createConnection(dbConfig);

  conn.connect(function (err) {
    if (err) {
      console.log("DB connect error", err);
      setTimeout(establishConnection, 2000);
    }
  });

  conn.on("error", function (err) {
    console.log("DB error", err);
    if (err.code === "PROTOCOL_CONNECTION_LOST") {
      establishConnection();
    }
    else {
      throw err;
    }
  });
}

establishConnection();

exports.addActions = function addActions(uuid, date, actions, world, pos) {
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
      if (err) {
        console.log("DB insert error", err);
        setTimeout(addActions.bind(null, uuid, date, actions, world, pos), 2000);
      }
    }
  );
};
