<?php

declare(strict_types=1);

namespace luckCode\database\provider;

use luckCode\database\provider\exceptions\ProviderInitializeException;
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

    /** @var ProviderInitializeException|null $initializeException */
    protected $initializeException;

    protected $connection;

    /**
     * Provider constructor.
     * @param array $conArgs
     * @param PluginBase $owner
     */
    public function __construct(array $conArgs, PluginBase $owner)
    {
        $this->ownerPlugin = $owner;
        if (!$this->tryConnect($conArgs)) {
            $this->initializeException = new ProviderInitializeException($this->lastError->getMessage(), $this->lastError->getCode());
        }
    }

    public function close(): bool
    {
        $this->showInfo('A conexão foi finalizada pelo sistema!');
        return true;
    }

    /**
     * @inheritDoc
     */
    public function showInfo(string $info)
    {
        $this->getLogger()->info('§7[Provider(§a' . $this->getType() . '§7)] §7' . $info);
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
    public function getOwnerPlugin(): PluginBase
    {
        return $this->ownerPlugin;
    }

    /**
     * @inheritDoc
     */
    public function showAlert(string $alert)
    {
        $this->getLogger()->info('§e[Provider(§a' . $this->getType() . '§e)] §7' . $alert);
    }

    /**
     * @inheritDoc
     */
    public function printError(Throwable $error)
    {
        $this->showError(implode("§r\n", [
            '§7' . $error->getMessage() . '§4(' . $error->getCode() . ')',
            "§c+-> §aIn line §f{$error->getLine()}§a from:",
            "§c+-> §e" . substr($error->getFile(), strpos($error->getFile(), 'luckCode')),
            "§8"
        ]));
    }

    /**
     * @inheritDoc
     */
    public function showError(string $error)
    {
        $this->getLogger()->info('§c[Provider(§a' . $this->getType() . '§c)] §7' . $error);
    }

    public function getRawConnection()
    {
        return $this->connection;
    }

    /**
     * @inheritDoc
     */
    public function fail()
    {
        return $this->lastError;
    }

    /**
     * @inheritDoc
     */
    public function failInitializeException()
    {
        return $this->initializeException;
    }
}