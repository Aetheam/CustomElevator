<?php

namespace Ayzrix\Elevator\API;

use Ayzrix\Elevator\Utils\Utils;
use pocketmine\block\Block;
use pocketmine\item\StringToItemParser;
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
        if ($elevator->asItem()->getTypeId() !== StringToItemParser::getInstance()->parse($block)->getTypeId() ) {
            return null;
        }

        return $elevator;
    }
}