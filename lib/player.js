'use strict';
var
config = require("../config.json"),
FS = require("fs"),
Path = require("path"),
NBT = require("nbt"),
HTTPS = require("https");

exports.load = function (uuid, callback) {
  FS.readFile(Path.join(config.worldPath, "playerdata", uuid + ".dat"), function (err, data) {
    if (err) throw err;

    NBT.parse(data, function (err, res) {
      callback(res);
    });
  });
};

function requestJSON(url, callback) {
  HTTPS.get(url).on("response", function (response) {
    var body = "";
    response.on("data", function (chunk) {
      body += chunk;
    });
    response.on("end", function () {
      var data = JSON.parse(body);

      callback(data);
    });
  });
}

// http://wiki.vg/Mojang_API

exports.getName = function (uuid, callback) {
  uuid = uuid.replace(/\-/g, "");

  requestJSON("https://api.mojang.com/user/profiles/" + uuid + "/names", function (data) {
    callback(data[data.length - 1].name);
  });
};

exports.getUUID = function (name, callback) {
  requestJSON("https://api.mojang.com/users/profiles/minecraft/" + name, function (data) {
    callback(data.id);
  });
};
