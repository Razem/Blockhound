'use strict';
var
config = require("../config.js"),
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

var addActions = exports.addActions = function (uuid, date, diff, world, pos) {

};
