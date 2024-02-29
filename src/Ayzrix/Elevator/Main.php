<?php

namespace Ayzrix\Elevator;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;

use Ayzrix\Elevator\Events\Listeners\PlayerListener;

class Main extends PluginBase{
    use SingletonTrait;

    protected const CONFIG_VERSION = "1.0.0";

    /**
     * @return void
     */
    public function onLoad(): void{ 
        self::setInstance($this); 
    }

    /**
     * @return void
     */
    public function onEnable(): void{
        $this->saveDefaultConfig();
        $this->loadCheck();
        $this->getServer()->getPluginManager()->registerEvents(new PlayerListener, $this);
    }

    /**
     * @return void
     */
    protected function loadCheck(): void{
        if((!$this->getConfig()->exists("config-version")) || ($this->getConfig()->get("config-version") != self::CONFIG_VERSION)){
            rename($this->getDataFolder() . "config.yml", $this->getDataFolder() . "config_old.yml");
            $this->saveResource("config.yml");
            $this->getLogger()->critical("Your configuration file is outdated.");
            $this->getLogger()->notice("Your old configuration has been saved as config_old.yml and a new configuration file has been generated. Please update accordingly.");
        }
    }
}
