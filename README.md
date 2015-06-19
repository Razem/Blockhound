# Blockhound

A simple external Minecraft logger.

## What does it do?
Its main functionality is to store the following actions of a player with his current position:
- mineBlock.*
- useItem.*
- deaths
- leaveGame
- chestOpened
- trappedChestTriggered
- hopperInspected
- dropperInspected
- dispenserInspected
- furnaceInteraction
- brewingstandInteraction
- mobKills

## Installation process
0. `npm install`
0. Create **config.json**
0. Create DB structure (using **db.sql**)
0. Make an Apache alias for the **ui** directory
0. **start.bat**

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
- More filter options in the UI
- Show names instead of the UUIDs in the UI

## Known issues
- Stats are updated only every 45 seconds (approximately)
