<?php

declare(strict_types=1);

namespace luckCode\database\types;

use luckCode\database\Database;
use luckCode\database\table\types\LuckTable;
use luckCode\listener\database\LuckDatabaseDisableEvent;
use luckCode\listener\database\LuckDatabaseEnableEvent;
use luckCode\listener\database\LuckDatabaseNotInitializeEvent;
use luckCode\LuckCodePlugin;

class LuckDatabase extends Database {

    /** @return string[]|array */
    public function getDefaultTables(): array {
        $enableDefaultTable = LuckCodePlugin::getInstance()->getDataManager()->get('database')->get('enable_test_table');
        return $enableDefaultTable ? [LuckTable::class] : [];
    }

    public function onPreLoadTables() {
        (new LuckDatabaseEnableEvent($this))->call();
    }

    public function onInvalidProvider() {
        (new LuckDatabaseNotInitializeEvent())->call();
    }

    /** @return bool */
    public function close(): bool {
        $ev = new LuckDatabaseDisableEvent($this);
        $ev->call();
        if ($ev->isCancelled()) return false;
        return parent::close();
    }

    /** @return LuckTable|null */
    public function getDefaultTable() {
        return $this->tables[LuckTable::$name] ?? null;
    }
}