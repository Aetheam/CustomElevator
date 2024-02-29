<?php

namespace Ayzrix\Elevator\Events\Listeners;

use pocketmine\player\Player;

use pocketmine\event\Listener;

use pocketmine\event\player\PlayerJumpEvent;
use pocketmine\event\player\PlayerToggleSneakEvent;

use pocketmine\math\Vector3;

use Ayzrix\Elevator\Main;
use Ayzrix\Elevator\API\ElevatorAPI;
use Ayzrix\Elevator\Utils\Utils;

class PlayerListener implements Listener{

    /**
     * @param PlayerJumpEvent $event
     * @return void
     */
    public function onPlayerJump(PlayerJumpEvent $event): void{
        $player = $event->getPlayer();
        $level = $player->getWorld();
        $blockId = strtolower(Utils::getIntoConfig("block"));
        $block = $level->getBlock($player->getPosition()->subtract(0, 1, 0));
        if(ElevatorAPI::getWorldsEnabled($player) && strtolower($block->getName()) === $blockId){
            $x = (int)floor($player->getPosition()->getX());
            $y = (int)floor($player->getPosition()->getY());
            $z = (int)floor($player->getPosition()->getZ());
            $maxY = $level->getMaxY();
            $found = false;
            $y++;
            for(; $y <= $maxY; $y++){
                if($found = (ElevatorAPI::isElevatorBlock($x, $y, $z, $level) !== null)){
                    break;
                }
            }
            if($found){
                $this->teleportPlayer($player, $x, $y, $z);
            }else{
                $player->sendMessage(Utils::getConfigMessage("no_elevator_found"));
            }
        }
    }

    /**
     * @param PlayerToggleSneakEvent $event
     * @return void
     */
    public function onPlayerToggleSneak(PlayerToggleSneakEvent $event): void{
        $player = $event->getPlayer();
        $level = $player->getWorld();
        $blockId = strtolower(Utils::getIntoConfig("block"));
        $block = $level->getBlock($player->getPosition()->subtract(0, 1, 0));
        if(ElevatorAPI::getWorldsEnabled($player) && $event->isSneaking() && strtolower($block->getName()) === $blockId){
            $x = (int)floor($player->getPosition()->getX());
            $y = (int)floor($player->getPosition()->getY())-2;
            $z = (int)floor($player->getPosition()->getZ());
            $found = false;
            $y--;
            for(; $y >= 0; $y--){
                if($found = (ElevatorAPI::isElevatorBlock($x, $y, $z, $level) !== null)){
                    break;
                }
            }
            if($found){
                $this->teleportPlayer($player, $x, $y, $z);
            }else{
                $player->sendMessage(Utils::getConfigMessage("no_elevator_found"));
            }
        }
    }
    
    /**
     * @param Player $player
     * @param integer $x
     * @param integer $y
     * @param integer $z
     * @return void
     */
    protected function teleportPlayer(Player $player, int $x, int $y, int $z): void{
        $config = Main::getInstance()->getConfig();
        $distanceConfig = Utils::getIntoConfig("distance");
        if($config->get("permission")){
            foreach($config->get("dist") as $perm => $dist){
                if($player->hasPermission($perm)){
                    if($distanceConfig === true){
                        if($player->getPosition()->distance(new Vector3($x + 0.5, $y + 1, $z + 0.5)) > intval($dist["max_distance"])){
                            $player->sendMessage(Utils::getConfigMessage("distance_too_hight"));
                            return;
                        }
                        $player->teleport(new Vector3($x + 0.5, $y + 1, $z + 0.5));
                    }else $player->teleport(new Vector3($x + 0.5, $y + 1, $z + 0.5));
                }
            }
        }else{
            if($distanceConfig === true){
                if($player->getPosition()->distance(new Vector3($x + 0.5, $y + 1, $z + 0.5)) > intval(Utils::getIntoConfig("max_distance"))){
                    $player->sendMessage(Utils::getConfigMessage("distance_too_hight"));
                    return;
                }
                $player->teleport(new Vector3($x + 0.5, $y + 1, $z + 0.5));
            }else $player->teleport(new Vector3($x + 0.5, $y + 1, $z + 0.5));
        }
    }
}