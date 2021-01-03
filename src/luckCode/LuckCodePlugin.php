<?php

declare(strict_types=1);

namespace luckCode;

use luckCode\data\save\manager\DataSaveWorker;
use pocketmine\plugin\PluginBase;

class LuckCodePlugin extends PluginBase
{

    /** @var LuckCodePlugin $instance */
    private static $instance;

    /** @return LuckCodePlugin */
    public static function getInstance() : LuckCodePlugin {
        return self::$instance;
    }

    public function onLoad()
    {
        self::$instance = $this;
    }

    public function onEnable()
    {

    }

    public function onDisable()
    {
        DataSaveWorker::startWorker();
    }
}