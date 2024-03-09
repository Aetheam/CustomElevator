<?php

namespace Ayzrix\Elevator\API;

use pocketmine\player\Player;

use pocketmine\block\Block;
use pocketmine\world\World;

use Ayzrix\Elevator\Utils\Utils;
use Ayzrix\Elevator\Main;

class ElevatorAPI{

    private const BLACKLIST = "blacklist";
    private const WHITELIST = "whitelist";

    /**
     * @param int $x
     * @param int $y
     * @param int $z
     * @param World $level
     * @return Block|null
     */
    public static function isElevatorBlock(int $x, int $y, int $z, World $level): ?Block{
        $elevator = $level->getBlockAt($x, $y, $z);
        $blockId = Utils::getIntoConfig("block");
        if(strtolower($elevator->getName()) !== strtolower($blockId)){
            return null;
        }
        return $elevator;
    }

    /**
     * @param Player $player
     * @return boolean
     */
    public static function getWorldsEnabled(Player $player): bool{
        $mode = Main::getInstance()->getConfig()->getNested("Settings.WorldManager.mode");
        if(boolval($mode) === false){
            return true;
        }
        return match (strtolower($mode)) {
            self::BLACKLIST => self::isBlacklistMode($player->getWorld()->getFolderName()),
            self::WHITELIST => self::isWhitelistMode($player->getWorld()->getFolderName()),
            default => false,
        };
    }
    
    /**
     * @param string $worldName
     * @return boolean
     */
    public static function isWhitelistMode(string $worldName): bool{
        $worldsWhitelist = Main::getInstance()->getConfig()->getNested("Settings.WorldManager.worlds-whitelist");
        return in_array($worldName, $worldsWhitelist);
    }
    
    /**
     * @param string $worldName
     * @return boolean
     */
    public static function isBlacklistMode(string $worldName): bool{
        $worldsBlacklist = Main::getInstance()->getConfig()->getNested("Settings.WorldManager.worlds-blacklist");
        return !in_array($worldName, $worldsBlacklist);
    }
}