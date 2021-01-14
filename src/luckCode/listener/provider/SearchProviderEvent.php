<?php

declare(strict_types=1);

namespace luckCode\listener\provider;

use luckCode\database\provider\types\MysqliProvider;
use luckCode\database\provider\types\PDOMysqliProvider;
use luckCode\database\provider\types\PDOSqlite3Provider;
use luckCode\database\provider\types\Sqlite3Provider;
use luckCode\listener\LuckEvent;
use pocketmine\event\HandlerList;
use function strtolower;

class SearchProviderEvent extends LuckEvent
{

    /** @var HandlerList $handlerList */
    public static $handlerList;

    /** @var string[] $list */
    private $list = [
        'pdo-mysqli' => PDOMysqliProvider::class,
        'pdo-sqlite3' => PDOSqlite3Provider::class,
        'mysqli' => MysqliProvider::class,
        'sqlite3' => Sqlite3Provider::class
    ];

    /**
     * @param string $type
     * @param string $classProvider
     */
    public function putType(string $type, string $classProvider)
    {
        $this->list[strtolower($type)] = $classProvider;
    }

    /**
     * @param string $type
     * @return string|null
     */
    public function getType(string $type)
    {
        return $this->list[strtolower($type)] ?? null;
    }

    /**
     * @param string $type
     * @return bool
     */
    public function removeType(string $type): bool
    {
        if (isset($this->list[$type])) {
            unset($this->list[$type]);
            return true;
        }
        return false;
    }
}