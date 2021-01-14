<?php

namespace luckCode\system\controller\interfaces;

use luckCode\system\System;
use pocketmine\plugin\PluginBase;

interface ISystemController
{
    /** @return PluginBase */
    public function getOwnerPlugin() : PluginBase;

    /** @return System[] */
    public function getSystems() : array;

    /**
     * @param string $name
     * @return System|null
     */
    public function getSystem(string $name);

    public function onLoad();

    public function onEnable();

    public function onDisable();
}