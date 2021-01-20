<?php

declare(strict_types=1);

namespace luckCode\database\provider\types;

use luckCode\database\provider\PDOProvider;
use PDO;

class PDOMysqliProvider extends PDOProvider {

    /** @return string */
    public function getType(): string {
        return 'PDO-Mysqli';
    }

    /**
     * @param array $args
     * @return string
     */
    public function getDSN(array $args): string {
        $host = strtolower($args['host'] ?? 'localhost');
        $port = $args['port'] ?? 3306;
        $dbName = strtolower($args['dbname'] ?? 'luckcode');
        return "mysql:host={$host};port={$port};dbname={$dbName}";
    }

    /** @return bool */
    public function isLocal(): bool {
        return false;
    }

    /** @return array */
    public function getDriverOptions(): array {
        return [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'',
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];
    }
}