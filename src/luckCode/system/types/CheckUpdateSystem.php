<?php

declare(strict_types=1);

namespace luckCode\system\types;

use luckCode\LuckCodePlugin;
use luckCode\scheduler\updater\LuckUpdater;
use luckCode\system\System;
use pocketmine\Server;

class CheckUpdateSystem extends System
{

    const NAME = 'CheckUpdate';

    public function onEnable()
    {
        $version = LuckCodePlugin::getInstance()->getDescription()->getVersion();
        Server::getInstance()->getScheduler()->scheduleAsyncTask(new LuckUpdater($version));
    }

}