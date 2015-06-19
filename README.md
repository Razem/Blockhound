# Blockhound

A simple external Minecraft logger.

## What does it do?
Its main functionality is to store the following actions of a player with his current position:
- mineBlock.*
- useItem.*
- chestOpened
- deaths

## Installation process
0. `npm install`
0. Create config.json
0. Create DB structure (using db.sql)
0. start.bat

### Example of config.json
```
{
  "worldPath": "C:/Minecraft Server/world",
  "database": {
    "host": "localhost",
    "user": "root",
    "pass": "",
    "db": "blockhound"
  }
}
```

## Todo
- Web interface for displaying actions

## Known issues
- Stats are updated only every 45 seconds (approximately)
