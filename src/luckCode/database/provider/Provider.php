<?php

declare(strict_types=1);

namespace luckCode\database\provider;

use luckCode\utils\InfoStatus;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\PluginLogger;
use Throwable;

abstract class Provider implements InfoStatus, interfaces\IProvider
{

    /** @var PluginBase $ownerPlugin */
    protected $ownerPlugin;

    /** @var Throwable $lastError */
    protected $lastError;

    protected $connection;

    /**
     * Provider constructor.
     * @param array $conArgs
     * @param PluginBase $owner
     */
    public function __construct(array $conArgs, PluginBase $owner)
    {
        $this->ownerPlugin = $owner;
        $this->tryConnect($conArgs);
    }

    /**
     * @inheritDoc
     */
    public function getOwnerPlugin(): PluginBase
    {
        return $this->ownerPlugin;
    }

    /**
     * @inheritDoc
     */
    public function getLogger(): PluginLogger
    {
        return $this->ownerPlugin->getLogger();
    }

    /**
     * @inheritDoc
     */
    public function showInfo(string $info)
    {
        $this->getLogger()->info(self::INFO_PREFIX.'§b[Provider-'.$this->getType().'] §7'.$info);
    }

    /**
     * @inheritDoc
     */
    public function showAlert(string $alert)
    {
        $this->getLogger()->info(self::ALERT_PREFIX.'§b[Provider-'.$this->getType().'] §7'.$alert);
    }

    /**
     * @inheritDoc
     */
    public function showError(string $error)
    {
        $this->getLogger()->info(self::ERROR_PREFIX.'§b[Provider(§a'.$this->getType().'§b)] §7'.$error);
    }

    /**
     * @inheritDoc
     */
    public function printError(Throwable $error)
    {
        $this->showError(implode("§r\n", [
            '§7'.$error->getMessage().'§4('.$error->getCode().')',
            "§c> §aIn line §f{$error->getLine()}§a from:",
            "§c> §e".substr($error->getFile(), strpos($error->getFile(), 'luckCode')),
            "§8"
        ]));
    }

    public function getRawConnection()
    {
        return $this->connection;
    }

    public function fail()
    {
        return $this->lastError;
    }
}