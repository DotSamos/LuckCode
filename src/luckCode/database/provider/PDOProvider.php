<?php

declare(strict_types=1);

namespace luckCode\database\provider;

use PDO;
use PDOException;

abstract class PDOProvider extends Provider {

    /** @var PDO $connection */
    protected $connection;

    /**
     * @param array $args
     * @return bool
     */
    public function tryConnect(array $args): bool {
        try {
            $pdo = new PDO($this->getDSN($args),
                $args['user'] ?? '',
                $args['password'] ?? '',
                $this->getDriverOptions()
            );
            $this->connection = $pdo;
            $this->showInfo('§aOK.');
            return true;
        } catch (PDOException $e) {
            $this->lastError = $e;
            $this->showError('§cFalha na conexão!');
            $this->printError($e);
            return false;
        }
    }

    /**
     * @param array $args
     * @return string
     */
    public abstract function getDSN(array $args): string;

    /** @return array */
    public abstract function getDriverOptions(): array;

    /** @return bool */
    public function close(): bool {
        $this->connection = null; // Sim, nem eu acreditei que é assim que fecha a conexão com o PDO
        parent::close();
        return true;
    }

    /**
     * @param string $exec
     * @return bool
     */
    public function exec(string $exec): bool {
        try {
            $result = $this->connection->exec($exec);
            return $result !== FALSE;
        } catch (PDOException $e) {
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
            return $fetchAll ? $result->fetchAll() : $result->fetch();
        } catch (PDOException $e) {
            $this->lastError = $e;
            $this->printError($e);
            return [];
        }
    }
}