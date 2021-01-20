<?php

declare(strict_types=1);

namespace luckCode\database\provider\types;

use Exception;
use luckCode\database\provider\exceptions\ProviderInitializeException;
use luckCode\database\provider\Provider;
use mysqli;
use mysqli_result;
use Throwable;

class MysqliProvider extends Provider {

    /** @var mysqli $connection */
    protected $connection;

    /** @return string */
    public function getType(): string {
        return 'Mysqli';
    }

    /**
     * @param array $args
     * @return bool
     */
    public function tryConnect(array $args): bool {
        $host = $args['host'] ?? 'localhost';
        $port = $args['port'] ?? 3306;
        $user = $args['user'] ?? 'root';
        $password = $args['password'] ?? '';
        $dbName = $args['dbname'] ?? 'luckcode';

        try {
            $con = @new mysqli($host, $user, $password, $dbName, $port);

            if ($con->connect_error ?? $con->error) {
                throw new ProviderInitializeException($con->connect_error ?? $con->error, $con->connect_errno ?? $con->errno);
            }
            if (!$con->get_charset()->charset == 'utf8') {
                if ($con->set_charset("utf8")) {
                    $this->showInfo('Codificação definida para UTF-8');
                } else {
                    $this->showAlert('Não foi possivel definir a codificação para UTF-8');
                }
            }
            $this->connection = $con;
            $this->showInfo('§aOK.');
            return true;
        } catch (Throwable $e) {
            $this->lastError = $e;
            $this->printError($e);
            return false;
        }
    }

    public function isLocal(): bool {
        return false;
    }

    /** @return bool */
    public function close(): bool {
        if ($this->connection) {
            parent::close();
            return $this->connection->close();
        }
        return false;
    }

    /**
     * @param string $exec
     * @return bool
     */
    public function exec(string $exec): bool {
        try {
            return (bool)$this->connection->query($exec);
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
            $finalResult = null;
            if ($result instanceof mysqli_result) {
                if ($fetchAll) {
                    $finalResult = [];
                    while ($r = $result->fetch_array(MYSQLI_ASSOC)) {
                        $finalResult[] = $r;
                    }
                } else {
                    $finalResult = $result->fetch_array(MYSQLI_ASSOC);
                }
            } else {
                throw new Exception($this->connection->error, $this->connection->errno, $this->lastError);
            }
            return $finalResult;
        } catch (Throwable $e) {
            $this->lastError = $e;
            $this->printError($e);
            return [];
        }
    }
}