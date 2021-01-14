<?php

declare(strict_types=1);

namespace luckCode\system;

use luckCode\system\interfaces\ISystem;
use pocketmine\plugin\PluginBase;

abstract class System implements ISystem
{

    /** @var PluginBase $ownerPlugin */
    protected $ownerPlugin;

    /**
     * System constructor.
     * @param PluginBase $ownerPlugin
     */
    public function __construct(PluginBase $ownerPlugin)
    {
        $this->ownerPlugin = $ownerPlugin;
    }

    public function onLoad()
    {

    }

    public function onEnable()
    {

    }

    public function onDisable()
    {

    }

    public function onReload()
    {

    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * @inheritDoc
     */
    public function getOwnerPlugin(): PluginBase
    {
        return $this->ownerPlugin;
    }
}