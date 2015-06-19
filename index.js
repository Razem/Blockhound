'use strict';
var
config = require("./config.json"),
FS = require("fs"),
Path = require("path"),
Stats = require("./lib/stats"),
Player = require("./lib/player"),
ReadLine = require("readline");

// Just for test

var last = Date.now();
var watcher = FS.watch(Path.join(config.worldPath, "stats"), function (event, fileName) {
  var now = Date.now();
  console.log(now, now - last, event, fileName);

  last = now;
});

// Command processing

var rl = ReadLine.createInterface({
  input: process.stdin,
  output: process.stdout
});

(function processCommands() {
  rl.question("", function (cmd) {
    if (cmd === "exit") {
//      watcher.close();
      process.exit(0);
      return;
    }

    // TODO: Process other commands
    console.log("Command", cmd);

    processCommands();
  });
})();
