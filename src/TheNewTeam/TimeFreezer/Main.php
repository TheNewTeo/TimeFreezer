<?php

namespace TheNewTeam\TimeFreezer;

use pocketmine\event\level\LevelLoadEvent;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener {

    /** @var int $time */
    public $time;

    /** @var string[] $levels */
    public $levels;

    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        @mkdir($this->getDataFolder());
        if (!file_exists($this->getDataFolder() . "config.yml")) {
            new Config($this->getDataFolder() . "config.yml", Config::YAML, ["time" => 6000, "worlds" => [$this->getServer()->getConfigString("level-name")]]);
        }
        $this->time = $this->getConfig()->get("time", 6000);
        $this->levels = $this->getConfig()->get("worlds", [$this->getServer()->getConfigString("level-name")]);
    }

    public function onLevelLoaded(LevelLoadEvent $event) {
        $level = $event->getLevel();
        if (in_array($name = $level->getFolderName(), $this->levels)) {
            $level->stopTime();
            $level->setTime($this->time);
            $this->getServer()->getLogger()->info("Time of level $name stopped at " . $this->time . " ticks.");
        }
    }
}
