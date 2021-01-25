<?php

declare(strict_types=1);

namespace luckCode\listener\database;

use luckCode\database\types\LuckDatabase;
use luckCode\listener\LuckEvent;

class LuckDatabaseEvent extends LuckEvent {

    /** @var LuckDatabase $database */
    protected $database;

    /**
     * ProviderEvent constructor.
     * @param LuckDatabase $database
     */
    public function __construct(LuckDatabase $database) {
        $this->database = $database;
    }

    /** @return LuckDatabase */
    public function getDatabase(): LuckDatabase {
        return $this->database;
    }

    /** @param LuckDatabase $database */
    public function setDatabase(LuckDatabase $database) {
        $this->database = $database;
    }
}