<?php

namespace luckCode\database\table\interfaces;

use pocketmine\plugin\PluginBase;

interface ITable
{
    /** @return string */
    public function name(): string;

    /** @return bool */
    public function isInitialized(): bool;

    /** @return string */
    public function getCreationExecute(): string;

    /** @return PluginBase */
    public function getPluginOwner(): PluginBase;

    /**
     * @param string $execute
     * @return bool
     */
    public function tryCreateTable(string $execute): bool;
}