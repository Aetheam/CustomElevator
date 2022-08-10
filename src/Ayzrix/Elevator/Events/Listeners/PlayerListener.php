<?php

namespace Ayzrix\Elevator\Events\Listeners;

use Ayzrix\Elevator\API\ElevatorAPI;
use Ayzrix\Elevator\Utils\Utils;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJumpEvent;
use pocketmine\event\player\PlayerToggleSneakEvent;
use pocketmine\math\Vector3;

class PlayerListener implements Listener {

    public function onPlayerJump(PlayerJumpEvent $event): bool {
        $player = $event->getPlayer();
        $level = $player->getWorld();
        $block = Utils::getIntoConfig("block");
        $block = explode(":",$block);
        $id = (int)$block[0];
        $damage = (int)$block[1];

        if ($level->getBlock($player->getPosition()->subtract(0, 1, 0))->getId() !== $id or (Utils::getIntoConfig("use_meta") === true and $level->getBlock($player->getPosition()->subtract(0, 1, 0))->getMeta() !== $damage)) return false;

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

        if ($found) {
            if(Utils::getIntoConfig("distance") === true) {
                if ($player->getPosition()->distance(new Vector3($x + 0.5, $y + 1, $z + 0.5)) <= (int)Utils::getIntoConfig("max_distance")) {
                    $player->teleport(new Vector3($x + 0.5, $y + 1, $z + 0.5));
                } else $player->sendMessage(Utils::getConfigMessage("distance_too_hight"));
            } else $player->teleport(new Vector3($x + 0.5, $y + 1, $z + 0.5));
        } else $player->sendMessage(Utils::getConfigMessage("no_elevator_found"));
        return true;
    }

    public function onPlayerToggleSneak(PlayerToggleSneakEvent $event): bool {
        $player = $event->getPlayer();
        $level = $player->getWorld();
        $block = Utils::getIntoConfig("block");
        $block = explode(":",$block);
        $id = (int)$block[0];
        $damage = (int)$block[1];

        if (!$event->isSneaking()) return false;
        if ($level->getBlock($player->getPosition()->subtract(0, 1,0))->getId() !== $id or (Utils::getIntoConfig("use_meta") === true and $level->getBlock($player->getPosition()->subtract(0, 1,0))->getMeta() !== $damage)) return false;

        $x = (int)floor($player->getPosition()->getX());
        $y = (int)floor($player->getPosition()->getY())-2;
        $z = (int)floor($player->getPosition()->getZ());
        $found = false;
        $y--;

        for (; $y >= 0; $y--) {
            if ($found = (ElevatorAPI::isElevatorBlock($x, $y, $z, $level) !== null)) {
                break;
            }
        }

        if ($found) {
            if(Utils::getIntoConfig("distance") === true) {
                if ($player->getPosition()->distance(new Vector3($x + 0.5, $y + 1, $z + 0.5)) <= (int)Utils::getIntoConfig("max_distance")) {
                    $player->teleport(new Vector3($x + 0.5, $y + 1, $z + 0.5));
                } else $player->sendMessage(Utils::getConfigMessage("distance_too_hight"));
            } else $player->teleport(new Vector3($x + 0.5, $y + 1, $z + 0.5));
        } else $player->sendMessage(Utils::getConfigMessage("no_elevator_found"));
        return true;
    }
}