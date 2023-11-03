<?php

namespace MathChat;

use pocketmine\console\ConsoleCommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;

class MathChatAPI implements Listener{
    public static $lastquestion = "";

    public static $calculs = [];

    public function __construct(){
        $signes = ["+", "-", "*"];
        for($a = 1; $a < 6; $a++){
            for($b = 1; $b < 6; $b++){
                $signe = $signes[array_rand($signes)];
                if($signe == "+"){
                    $r = $a + $b;
                }else if($signe == "-"){
                    $r = $a - $b;
                }else{
                    $r = $a * $b;
                }
                self::$calculs[$a." ".$signe." ".$b] = $r;
            }
        }

        Main::$main->getServer()->getPluginManager()->registerEvents($this, Main::$main);
        Main::$main->getScheduler()->scheduleRepeatingTask(new MathChatTask(
            Main::$config->get("delay")
        ), 20);
    }

    public static function AskMessage(string $calcul){
        Main::$main->getServer()->broadcastMessage(
            str_replace("{calcul}", $calcul, Main::$config->get("ask"))
        );
    }

    public static function ask(){
        $array = array_keys(self::$calculs);
        $math = $array[array_rand($array)];
        self::AskMessage($math);
        self::$lastquestion = self::$calculs[$math];
    }

    public function isValid(PlayerChatEvent $event){
        if($event->getMessage() == self::$lastquestion){
            Main::$main->getServer()->dispatchCommand(
                new ConsoleCommandSender(
                    Main::$main->getServer(),
                    Main::$main->getServer()->getLanguage()
                ),
                str_replace(
                    "{player}", 
                    $event->getPlayer()->getName(), 
                    Main::$config->get("win_cmd")
                )
            );
            Main::$main->getServer()->broadcastMessage(
                str_replace(
                    [
                        "{player}",
                        "{resultat}"
                    ],
                    [
                        $event->getPlayer()->getName(),
                        self::$lastquestion,
                    ],
                    Main::$config->get("win")
                )
            );
            self::$lastquestion = "";
            $event->cancel();
            return true;
        }else{
            return false;
        }
    }
}