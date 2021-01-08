<?php

declare(strict_types=1);

namespace luckCode\database\provider\types;

use luckCode\database\provider\PDOProvider;
use PDO;

class PDOMysqliProvider extends PDOProvider
{

    /**
     * @inheritDoc
     */
    public function getType(): string
    {
        return 'PDO-Mysqli';
    }

    /**
     * @inheritDoc
     */
    public function getDSN(array $args): string
    {
        $host = strtolower($args['host'] ?? 'localhost');
        $port = $args['port'] ?? 3306;
        $dbName = strtolower($args['dbname'] ?? 'luckcode');
        return "mysql:host={$host};port={$port};dbname={$dbName}";
    }

    public function isLocal(): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function getDriverOptions(): array
    {
        return [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'',
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];
    }
}