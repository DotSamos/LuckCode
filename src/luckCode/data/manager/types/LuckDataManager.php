<?php

declare(strict_types=1);

namespace luckCode\data\manager\types;

use luckCode\data\manager\DataManager;
use luckCode\data\types\YamlData;
use luckCode\LuckCodePlugin;
use function array_walk;

class LuckDataManager extends DataManager
{
    public function loadDefaults()
    {
        $pl = LuckCodePlugin::getInstance();
        $defaults = ['database', 'entities', 'menu', 'systems', 'freezeTime'];
        array_walk($defaults, function (string $file) use ($pl) {
            $this->cache[$file] = new YamlData($file, $pl->getDataFolder(), $pl);
        });
    }
}