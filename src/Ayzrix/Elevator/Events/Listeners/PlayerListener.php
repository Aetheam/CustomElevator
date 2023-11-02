<?php

namespace Ayzrix\Elevator\Events\Listeners;

use Ayzrix\Elevator\API\ElevatorAPI;
use Ayzrix\Elevator\Main;
use Ayzrix\Elevator\Utils\Utils;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJumpEvent;
use pocketmine\event\player\PlayerToggleSneakEvent;
use pocketmine\item\StringToItemParser;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

class PlayerListener implements Listener
{

    public function onPlayerJump(PlayerJumpEvent $event): bool
    {
        $player = $event->getPlayer();
        $level = $player->getWorld();
        $block = Utils::getIntoConfig("block");

        if ($level->getBlock($player->getPosition()->subtract(0, 1, 0))->asItem()->getTypeId() === StringToItemParser::getInstance()->parse($block)->getTypeId()) {
            $x = (int)floor($player->getPosition()->getX());
            $y = (int)floor($player->getPosition()->getY());
            $z = (int)floor($player->getPosition()->getZ());
            $maxY = $level->getMaxY();
            $found = false;
            $y++;
            for (; $y <= $maxY; $y++) {
                if ($found = (ElevatorAPI::isElevatorBlock($x, $y, $z, $level) !== null)) {
                    break;
                }
            }
            return $this->extracted($found, $player, $x, $y, $z);
        } else {
            return false;
        }
    }

    public function onPlayerToggleSneak(PlayerToggleSneakEvent $event): bool
    {
        $player = $event->getPlayer();
        $level = $player->getWorld();
        $block = Utils::getIntoConfig("block");
        if (!$event->isSneaking()) return false;
        if ($level->getBlock($player->getPosition()->subtract(0, 1, 0))->asItem()->getTypeId() !== StringToItemParser::getInstance()->parse($block)->getTypeId()) return false;
        $x = (int)floor($player->getPosition()->getX());
        $y = (int)floor($player->getPosition()->getY()) - 2;
        $z = (int)floor($player->getPosition()->getZ());
        $found = false;
        $y--;

        for (; $y >= 0; $y--) {
            if ($found = (ElevatorAPI::isElevatorBlock($x, $y, $z, $level) !== null)) {
                break;
            }
        }

        return $this->extracted($found, $player, $x, $y, $z);
    }

    /**
     * @param bool $found
     * @param Player $player
     * @param int $x
     * @param int $y
     * @param int $z
     * @return true
     */
    public function extracted(bool $found, Player $player, int $x, int $y, int $z): bool
    {
        if (Main::getInstance()->getConfig()->get("permission")) {
            foreach (Main::getInstance()->getConfig()->get("dist") as $perm => $dist) {
                if ($player->hasPermission($perm)) {
                    if ($found) {
                        if (Utils::getIntoConfig("distance") === true) {
                            if ($player->getPosition()->distance(new Vector3($x + 0.5, $y + 1, $z + 0.5)) <= (int)$dist["max_distance"]) {
                                $player->teleport(new Vector3($x + 0.5, $y + 1, $z + 0.5));
                            } else $player->sendMessage(Utils::getConfigMessage("distance_too_hight"));
                        } else $player->teleport(new Vector3($x + 0.5, $y + 1, $z + 0.5));
                    } else $player->sendMessage(Utils::getConfigMessage("no_elevator_found"));
                }
            }
        } else {
            if ($found) {
                if (Utils::getIntoConfig("distance") === true) {
                    if ($player->getPosition()->distance(new Vector3($x + 0.5, $y + 1, $z + 0.5)) <= (int)Utils::getIntoConfig("max_distance")) {
                        $player->teleport(new Vector3($x + 0.5, $y + 1, $z + 0.5));
                    } else $player->sendMessage(Utils::getConfigMessage("distance_too_hight"));
                } else $player->teleport(new Vector3($x + 0.5, $y + 1, $z + 0.5));
            } else $player->sendMessage(Utils::getConfigMessage("no_elevator_found"));
        }
        return true;
    }
}