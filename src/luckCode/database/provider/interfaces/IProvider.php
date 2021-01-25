<?php

declare(strict_types=1);

namespace luckCode\database\provider\interfaces;

use Exception;
use luckCode\database\provider\exceptions\ProviderInitializeException;
use pocketmine\plugin\PluginBase;

interface IProvider {

    /** @return PluginBase */
    public function getOwnerPlugin(): PluginBase;

    public function getRawConnection();

    /** @return string */
    public function getType(): string;

    /** @return Exception|null */
    public function fail();

    /** @return ProviderInitializeException|null */
    public function failInitializeException();

    /** @return bool */
    public function isLocal(): bool;

    /**
     * @param array $args
     * @return bool
     */
    public function tryConnect(array $args): bool;

    /** @return bool */
    public function close(): bool;

    /**
     * @param string $exec
     * @return bool
     */
    public function exec(string $exec): bool;

    /**
     * @param string $query
     * @param bool $fetchAll
     * @return array
     */
    public function executeQuery(string $query, $fetchAll = false): array;
}