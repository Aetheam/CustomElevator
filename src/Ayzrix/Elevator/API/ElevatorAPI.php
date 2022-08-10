<?php

namespace Ayzrix\Elevator\API;

use Ayzrix\Elevator\Utils\Utils;
use pocketmine\block\Block;
use pocketmine\world\World;

class ElevatorAPI {

    /**
     * @param int $x
     * @param int $y
     * @param int $z
     * @param World $level
     * @return Block|null
     */
    public static function isElevatorBlock(int $x, int $y, int $z, World $level): ?Block {
        $elevator = $level->getBlockAt($x, $y, $z);
        $block = Utils::getIntoConfig("block");
        $block = explode(":",$block);
        $id = (int)$block[0];
        $damage = (int)$block[1];

        if ($elevator->getId() !== $id or (Utils::getIntoConfig("use_meta") === true and $elevator->getMeta() !== $damage)) {
            return null;
        }

        return $elevator;
    }
}