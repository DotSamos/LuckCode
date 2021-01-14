<?php

declare(strict_types=1);

namespace luckCode\updater;

use luckCode\LuckCodePlugin;
use luckCode\scheduler\updater\LuckUpdater;

final class AutoUpdater
{

    public static function check()
    {
        $plugin = LuckCodePlugin::getInstance();
        $data = $plugin->getDataManager();
        $server = $plugin->getServer();

        if ($data->get('updater')->get('update-enable')) {
            $version = $plugin->getDescription()->getVersion();
            $server->getScheduler()->scheduleAsyncTask(new LuckUpdater($version));
        }
    }
}