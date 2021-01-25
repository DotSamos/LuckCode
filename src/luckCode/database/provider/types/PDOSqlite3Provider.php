<?php

declare(strict_types=1);

namespace luckCode\database\provider\types;

use luckCode\database\provider\PDOProvider;
use PDO;

class PDOSqlite3Provider extends PDOProvider {

    /** @return string */
    public function getType(): string {
        return 'PDO-Sqlite3';
    }

    /**
     * @param array $args
     * @return string
     */
    public function getDSN(array $args): string {
        $path = $args['path'] ?? $this->getOwnerPlugin()->getDataFolder();
        $file = $args['file'] ?? 'database.db';
        if (!is_dir($path)) mkdir($path);
        $finalPath = $path . DIRECTORY_SEPARATOR . $file;
        return "sqlite:{$finalPath}";
    }

    /** @return bool */
    public function isLocal(): bool {
        return true;
    }

    /**
     * @param array $args
     * @return bool
     */
    public function tryConnect(array $args): bool {
        $args['user'] = '';
        $args['password'] = '';
        return parent::tryConnect($args);
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