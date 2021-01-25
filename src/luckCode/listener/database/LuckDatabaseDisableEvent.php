<?php

declare(strict_types=1);

namespace luckCode\listener\database;

use pocketmine\event\Cancellable;
use pocketmine\event\HandlerList;

class LuckDatabaseDisableEvent extends LuckDatabaseEvent implements Cancellable {

    /** @var HandlerList|null $handlerList */
    public static $handlerList;
}