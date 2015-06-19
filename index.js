'use strict';
var
config = require("./config.json"),
dirs = require("./lib/dirs.json"),
FS = require("fs"),
Path = require("path"),
Stats = require("./lib/stats"),
Player = require("./lib/player"),
Logger = require("./lib/logger"),
ReadLine = require("readline");

// Init
Logger.init();

// Command processing
var rl = ReadLine.createInterface({
  input: process.stdin,
  output: process.stdout
});

(function processCommands() {
  rl.question("", function (cmd) {
    if (cmd === "exit") {
      Logger.stopWatcher();
      process.exit(0);
      return;
    }

    // TODO: Process other commands
    console.log("Unknown command", cmd);

    processCommands();
  });
})();
