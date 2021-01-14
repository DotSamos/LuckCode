<?php

namespace luckCode\plugin\interfaces;

use luckCode\listener\database\LuckDatabaseDisableEvent;
use luckCode\listener\database\LuckDatabaseEnableEvent;
use luckCode\listener\database\LuckDatabaseNotInitializeEvent;

interface LuckDatabaseRequire
{
    /** @param LuckDatabaseEnableEvent $e */
    public function onConnectDatabase(LuckDatabaseEnableEvent $e);

    /** @param LuckDatabaseDisableEvent $e */
    public function onDatabaseClose(LuckDatabaseDisableEvent $e);

    /** @param LuckDatabaseNotInitializeEvent $e */
    public function onDatabaseConnectionError(LuckDatabaseNotInitializeEvent $e);

    /** @return string[] */
    public function getBaseTables() : array;
}