<?php

declare(strict_types=1);

namespace luckCode\scheduler\loader;

use luckCode\LuckCodePlugin;
use luckCode\scheduler\LuckTask;
use pocketmine\plugin\PluginBase;
use function array_filter;
use function count;

class DatabaseLoaderWaitTask extends LuckTask
{
    /**
     * @inheritDoc
     */
    public function onRun($currentTick)
    {
        $pl = LuckCodePlugin::getInstance();
        $wait = array_filter($pl->getServer()->getPluginManager()->getPlugins(), function (PluginBase $check){
           return !$check->isEnabled();
        });
        if(count($wait) == 0) {
            $pl->loadDatabase();
            $this->cancel();
        }
    }
}