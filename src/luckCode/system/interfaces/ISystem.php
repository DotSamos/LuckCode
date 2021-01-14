<?php

namespace luckCode\system\interfaces;

use pocketmine\plugin\PluginBase;

interface ISystem
{

    /** @return string */
    public function getName(): string;

    /** @return PluginBase */
    public function getOwnerPlugin(): PluginBase;

    public function onLoad();

    public function onEnable();

    public function onDisable();

    public function onReload();
}