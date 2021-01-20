<?php

declare(strict_types=1);

namespace luckCode\listener\database;

use luckCode\listener\LuckEvent;
use pocketmine\event\HandlerList;

class LuckDatabaseNotInitializeEvent extends LuckEvent {

    /** @var HandlerList $handlerList */
    public static $handlerList;
}