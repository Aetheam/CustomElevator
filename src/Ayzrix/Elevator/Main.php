<?php

namespace Ayzrix\Elevator;

use Ayzrix\Elevator\Events\Listeners\PlayerListener;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

    /** @var Main $instance */
    private static Main $instance;

    public function onEnable():void {
        self::$instance = $this;
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents(new PlayerListener(), $this);
    }

    /**
     * @return Main
     */
    public static function getInstance(): Main {
        return self::$instance;
    }
}
