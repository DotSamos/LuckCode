<?php

declare(strict_types=1);

namespace luckCode\utils;

use pocketmine\plugin\PluginLogger;
use Throwable;

interface InfoStatus
{

    const INFO_PREFIX = '§7[Info] ';
    const ALERT_PREFIX = '§e[Alert] ';
    const ERROR_PREFIX = '§c[Error] ';

    /** @return PluginLogger */
    public function getLogger() : PluginLogger;

    /** @param string $info */
    public function showInfo(string $info);

    /** @param string $alert */
    public function showAlert(string $alert);

    /** @param string $error */
    public function showError(string $error);

    /** @param Throwable $error */
    public function printError(Throwable $error);
}