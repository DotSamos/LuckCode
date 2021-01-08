<?php

declare(strict_types=1);

namespace luckCode\listener\database;

use luckCode\database\provider\interfaces\IProvider;
use luckCode\database\types\LuckDatabase;
use luckCode\listener\LuckEvent;

class LuckDatabaseEvent extends LuckEvent
{

    /** @var IProvider $database */
    protected $database;

    /**
     * ProviderEvent constructor.
     * @param LuckDatabase $database
     */
    public function __construct(LuckDatabase $database)
    {
        $this->database = $database;
    }

    /** @return IProvider */
    public function getDatabase() : IProvider {
        return $this->database;
    }

    /** @param LuckDatabase $database */
    public function setDatabase(LuckDatabase $database) {
        $this->database = $database;
    }
}