<?php

declare(strict_types=1);

namespace luckCode\database;

use luckCode\database\provider\interfaces\IProvider;
use luckCode\database\table\interfaces\ITable;
use luckCode\database\table\Table;
use luckCode\utils\InfoStatus;
use luckCode\utils\Utils;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\PluginLogger;
use Throwable;
use function array_walk;
use function count;
use function implode;

abstract class Database implements interfaces\IDatabase, InfoStatus {

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
    public function __construct(IProvider $provider, PluginBase $ownerPlugin) {
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

    /** @param string $error */
    public function showError(string $error) {
        $this->getLogger()->info('§c[Database] §7' . $error);
    }

    /** @return PluginLogger */
    public function getLogger(): PluginLogger {
        return $this->ownerPlugin->getLogger();
    }

    public function onInvalidProvider() {
    }

    public function onPreLoadTables() {
    }

    /** @return bool */
    public function loadTables(): bool {
        $all = $this->getDefaultTables();
        array_walk($all, function (string $table) {
            /** @var Table $table */
            $table = new $table($this->provider, $this->ownerPlugin);
            $this->addTable($table);
        });
        if (count($all) > 0) $this->showInfo('Foram carregadas §f' . count($this->tables) . '§7 tabelas!');
        return count($this->tables) == count($all);
    }

    /**
     * @param ITable $table
     * @return bool
     */
    public function addTable(ITable $table): bool {
        if (!isset($this->tables[$name = $table::NAME])) {
            $this->tables[$name] = $table;
            return true;
        }
        return false;
    }

    /** @param string $info */
    public function showInfo(string $info) {
        $this->getLogger()->info('§7[Database] §7' . $info);
    }

    /** @return bool */
    public function close(): bool {
        return $this->provider->close();
    }

    /** @return IProvider */
    public function getProvider(): IProvider {
        return $this->provider;
    }

    /** @return PluginBase */
    public function getOwnerPlugin(): PluginBase {
        return $this->ownerPlugin;
    }

    /**
     * @param string $name
     * @return ITable|null
     */
    public function getTable(string $name) {
        return $this->tables[$name] ?? null;
    }

    /** @param string $alert */
    public function showAlert(string $alert) {
        $this->getLogger()->info('§e[Database] §7' . $alert);
    }

    /** @param Throwable $error */
    public function printError(Throwable $error) {
        $this->showError(Utils::getThrowablePrint($error));
    }
}