<?php

declare(strict_types=1);

namespace luckCode\system\types;

use luckCode\data\types\YamlData;
use luckCode\LuckCodePlugin;
use luckCode\system\System;

class FreezeTimeSystem extends System {

    const NAME = 'FreezeTime';

    /** @var int[] */
    public static $worlds = [];

    /** @var YamlData $data */
    private $data;

    public function onEnable() {
        $data = LuckCodePlugin::getInstance()->getDataManager()->get('freezeTime');
        $data->addInSaveWorker();
        $this->data = $data;
        self::$worlds = $data->getContents();
    }

    public function onDisable() {
        $this->data->setContents(self::$worlds);
    }
}