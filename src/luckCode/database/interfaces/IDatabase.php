<?php

namespace luckCode\database\interfaces;

use luckCode\database\provider\interfaces\IProvider;
use luckCode\database\table\interfaces\ITable;
use pocketmine\plugin\PluginBase;

interface IDatabase {

    /** @return IProvider */
    public function getProvider(): IProvider;

    /** @return PluginBase */
    public function getOwnerPlugin(): PluginBase;

    /** @return bool */
    public function close(): bool;

    public function onPreLoadTables();

    public function onInvalidProvider();

    /**
     * @param ITable $table
     * @return bool
     */
    public function addTable(ITable $table): bool;

    /**
     * @param string $name
     * @return ITable|null
     */
    public function getTable(string $name);

    /** @return string[] */
    public function getDefaultTables(): array;

    /**
     * @return bool
     */
    public function loadTables(): bool;
}