<?php

namespace MathChat;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase{
    public static $config;

    public static $main;

    public function onEnable() : void{
        $this->saveDefaultConfig();
        self::$config = $this->getConfig();
        self::$main = $this;

        new MathChatAPI();
    }
}