<?php

declare(strict_types=1);

namespace luckCode\database\provider\types;

use Exception;
use luckCode\database\provider\Provider;
use SQLite3;
use SQLite3Result;
use Throwable;

class Sqlite3Provider extends Provider {

    /** @var SQLite3 $connection */
    protected $connection;

    /** @return string */
    public function getType(): string {
        return 'Sqlite3';
    }

    /**
     * @param array $args
     * @return bool
     */
    public function tryConnect(array $args): bool {
        $path = $args['path'] ?? $this->ownerPlugin->getDataFolder();
        $file = $args['file'] ?? 'database.db';
        if (!is_dir($path)) mkdir($path);
        try {
            $con = @new SQLite3($path . DIRECTORY_SEPARATOR . $file);
            $con->enableExceptions(true);
            $this->connection = $con;
            $this->showInfo('§aOK.');
            return true;
        } catch (Throwable $e) {
            $this->lastError = $e;
            $this->printError($e);
            return false;
        }
    }

    /** @return bool */
    public function close(): bool {
        if ($this->connection) {
            parent::close();
            return $this->connection->close();
        }
        return false;
    }

    /** @return bool */
    public function isLocal(): bool {
        return true;
    }

    /**
     * @param string $exec
     * @return bool
     */
    public function exec(string $exec): bool {
        try {
            return $this->connection->exec($exec);
        } catch (Throwable $e) {
            $this->lastError = $e;
            $this->printError($e);
            return false;
        }
    }

    /**
     * @param string $query
     * @param bool $fetchAll
     * @return array
     */
    public function executeQuery(string $query, $fetchAll = false): array {
        try {
            $result = $this->connection->query($query);
            $finalResult = [];
            if ($result instanceof SQLite3Result) {
                if ($fetchAll) {
                    while ($r = $result->fetchArray(SQLITE3_ASSOC)) {
                        $finalResult[] = $r;
                    }
                } else {
                    $finalResult = ($r = $result->fetchArray(SQLITE3_ASSOC)) == false ? [] : $r;
                }
            } else {
                throw new Exception($this->connection->lastErrorMsg(), $this->connection->lastErrorCode());
            }
            return $finalResult;
        } catch (Throwable $e) {
            $this->lastError = $e;
            $this->printError($e);
            return [];
        }
    }
}