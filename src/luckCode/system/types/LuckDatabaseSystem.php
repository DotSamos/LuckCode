<?php

declare(strict_types=1);

namespace luckCode\system\types;

use luckCode\LuckCodePlugin;
use luckCode\scheduler\loader\DatabaseLoaderWaitTask;
use luckCode\system\System;

class LuckDatabaseSystem extends System
{

    const NAME = 'DataBase';

    public function onEnable()
    {
        (new DatabaseLoaderWaitTask())->registerToRepeat(1);
    }

    public function onDisable()
    {
        $db = LuckCodePlugin::getInstance()->getDatabase();
        if ($db) $db->close();
    }
}