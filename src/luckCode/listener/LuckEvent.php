<?php

declare(strict_types=1);

namespace luckCode\listener;

use pocketmine\event\Event;
use pocketmine\Server;

abstract class LuckEvent extends Event {

    public function call() {
        Server::getInstance()->getPluginManager()->callEvent($this);
    }

}