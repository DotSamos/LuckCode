<?php

declare(strict_types=1);

namespace luckCode\database;

use luckCode\database\provider\interfaces\IProvider;
use luckCode\database\table\interfaces\ITable;
use luckCode\database\table\Table;
use luckCode\utils\InfoStatus;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\PluginLogger;
use Throwable;
use function array_walk;
use function count;
use function implode;
use function strpos;
use function substr;

abstract class Database implements interfaces\IDatabase, InfoStatus
{

    /** @var IProvider $provider */
    protected $provider;

    /** @var PluginBase $ownerPlugin */
    protected $ownerPlugin;

    /** @var ITable[] $tables */
    protected $tables = [];

    /**
     * Database constructor.
     * @param IProvider $provider
     * @param PluginBase $ownerPlugin
     */
    public function __construct(IProvider $provider, PluginBase $ownerPlugin)
    {
        $this->ownerPlugin = $ownerPlugin;
        if ($provider->failInitializeException()) {
            $this->showError('§cNão é possivel iniciar a database com o provedor ' . $provider->getType() . '§c. Conexão falha!');
            $this->onInvalidProvider();
        } else {
            $this->provider = $provider;
            $this->onPreLoadTables();
            $this->loadTables();
        }
    }

    public function onPreLoadTables()
    {
    }

    public function onInvalidProvider()
    {
    }

    public function close(): bool
    {
        return $this->provider->close();
    }

    /**
     * @inheritDoc
     */
    public function getProvider(): IProvider
    {
        return $this->provider;
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
    public function addTable(ITable $table): bool
    {
        if (!isset($this->tables[$name = $table::NAME])) {
            $this->tables[$name] = $table;
            return true;
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    public function getTable(string $name)
    {
        return $this->tables[$name] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function loadTables(): bool
    {
        $all = $this->getDefaultTables();
        array_walk($all, function (string $table) {
            /** @var Table $table */
            $table = new $table($this->provider, $this->ownerPlugin);
            $this->addTable($table);
        });
        if (count($all) > 0) $this->showInfo('Foram carregadas §f' . count($this->tables) . '§7 de §f' . count($all) . ' §7 tabelas!');
        return count($this->tables) == count($all);
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
        $this->getLogger()->info('§7[Database] §7' . $info);
    }

    /**
     * @inheritDoc
     */
    public function showAlert(string $alert)
    {
        $this->getLogger()->info('§e[Database] §7' . $alert);
    }

    /**
     * @inheritDoc
     */
    public function showError(string $error)
    {
        $this->getLogger()->info('§c[Database] §7' . $error);
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
}