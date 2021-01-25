<?php

declare(strict_types=1);

namespace luckCode\system\controller;

use luckCode\plugin\interfaces\LuckSystemLoader;
use luckCode\system\System;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use function array_filter;
use function array_walk;
use function count;
use function filter_var;
use const FILTER_VALIDATE_BOOLEAN;

class SystemController implements interfaces\ISystemController {

    /** @var PluginBase $ownerPlugin */
    protected $ownerPlugin;

    /** @var System[] $systems */
    protected $systems = [];

    /**
     * SystemController constructor.
     * @param PluginBase $ownerPlugin
     */
    public function __construct(PluginBase $ownerPlugin) {
        $this->ownerPlugin = $ownerPlugin;

        if ($ownerPlugin instanceof LuckSystemLoader) {
            $this->loadBaseSystems($ownerPlugin->getSystemsBases());
        }
    }

    private function loadBaseSystems(array $systems) {
        $systems = array_filter($systems, function (string $class) {
            $name = $class::NAME;
            return $this->ownerPlugin instanceof LuckSystemLoader &&
                filter_var($this->ownerPlugin->getSystemStatusList()[$name], FILTER_VALIDATE_BOOLEAN);
        });
        foreach ($systems as $class) {
            $this->systems[$class::NAME] = new $class($this->ownerPlugin);
        }
    }

    /** @return PluginBase */
    public function getOwnerPlugin(): PluginBase {
        return $this->ownerPlugin;
    }

    /** @return System[] */
    public function getSystems(): array {
        return $this->systems;
    }

    /**
     * @param string $name
     * @return System|null
     */
    public function getSystem(string $name) {
        return $this->systems[$name] ?? null;
    }

    public function onLoad() {
        array_walk($this->systems, function (System $system) {
            $system->onLoad();
        });
    }

    public function onEnable() {
        array_walk($this->systems, function (System $system) {
            $system->onEnable();
            if (count(Server::getInstance()->getOnlinePlayers()) > 0) { // se tem jogadores online é porque o alguem deu /reload :v
                $system->onReload();
            }
        });
    }

    public function onDisable() {
        array_walk($this->systems, function (System $system) {
            $system->onDisable();
        });
    }
}