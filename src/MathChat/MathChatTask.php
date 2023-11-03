<?php

namespace MathChat;
use pocketmine\scheduler\Task;

class MathChatTask extends Task{
    public $delay;

    public $time = 0;

    public function __construct(int $delay){
        $this->delay = $delay;
    }

    public function onRun() : void{
        if($this->time <= 0){
            $this->time = $this->delay;
            MathChatAPI::ask();
        }
        $this->time--;
    }
}