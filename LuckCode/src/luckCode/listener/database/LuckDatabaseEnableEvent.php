<?php

declare(strict_types=1);

namespace luckCode\listener\database;

use pocketmine\event\HandlerList;

class LuckDatabaseEnableEvent extends LuckDatabaseEvent
{
    /** @var HandlerList $handlerList */
    public static $handlerList;
}