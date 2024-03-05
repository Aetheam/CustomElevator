[![Discord](https://img.shields.io/discord/915046808009441323.svg?label=&logo=discord&logoColor=ffffff&color=7389D8&labelColor=6A7EC2)](https://discord.gg/AzJ7Uz7wkx)

# changelog
version 1.3.0 :
- add world configuration 
- modification of config.yml

# Elevator
A simple PocketMine-MP plugin that allows to create elevators on your server.

## How to use
You just have to setup an id in the config.yml. Place 2 blocks aligned, jump to go up and sneak to go down.

## Video
[![Alt text](https://img.youtube.com/vi/9rcDk5-vRqc/0.jpg)](https://www.youtube.com/watch?v=9rcDk5-vRqc&ab_channel=Ayzrix)

## Config
```yaml
---
# DON'T TOUCH!
config-version: "1.0.0"
prefix: "§6[§fElevatorBlock§6]"

# Elevator block name
block: "diamond block"

# Enable or disable the distance system (true|false)
distance: true

# Maximum distance between 2 elevators
max_distance: 5

# permission to use elevator
permission: true
#if you would use distance by a rank and if permission is true

#dist:
#  permission1:
#    if distance = true
#    max_distance: 4
#  permission2:
#    if distance = true
#    max_distance: 4
dist:
  player_6_block:
    max_distance: 5
  player_7_block:
    max_distance: 7

# ==(CONFIGURATION)==
Settings:
  # ==(WORLD MANAGER)==
  # add to "whitelist" or "blacklist" modes
  WorldManager:
    # Valid modes:
    # - whitelist
    # - blacklist
    # - false
    mode: whitelist
    # Add the names of worlds that are in the whitelist
    worlds-whitelist:
      - "world"
      - "world-2"
      - "ACM"
    # Add the names of worlds that are in the blacklist
    worlds-blacklist:
      - "MinePvP"
      - "ZonePvP"

no_elevator_found: "{prefix} §cNo elevator was found"
distance_too_hight: "{prefix} §cAn elevator has been found, but it's too far"

```

