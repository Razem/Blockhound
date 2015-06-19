# Blockhound

A simple external Minecraft logger.

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

## Known issues
- Stats are updated only every 45 seconds (approximately)
