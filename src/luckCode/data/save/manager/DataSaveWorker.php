<?php

declare(strict_types=1);

namespace luckCode\data\save\manager;

use luckCode\data\interfaces\IData;
use luckCode\data\save\task\DataSaveTaskAsync;
use luckCode\LuckCodePlugin;
use pocketmine\Server;
use function array_filter;
use function count;
use function microtime;
use function number_format;

class DataSaveWorker {

    /** @var DataSaveTaskAsync[] $taskList */
    public static $taskList = [];

    /** @var IData[] $cache */
    private static $cache = [];

    /**
     * @param IData $data
     * @return bool
     */
    public static function put(IData $data): bool {
        if (!self::contains($data)) {
            self::$cache[spl_object_hash($data)] = $data;
            return true;
        }
        return false;
    }

    /**
     * @param IData $data
     * @return bool
     */
    public static function contains(IData $data): bool {
        return isset(self::$cache[spl_object_hash($data)]);
    }

    /**
     * @param IData $data
     * @return bool
     */
    public static function remove(IData $data): bool {
        if (!self::contains($data)) {
            unset(self::$cache[spl_object_hash($data)]);
            return true;
        }
        return false;
    }

    public static function startWorker() {
        if (!empty(self::$cache)) {
            $logger = LuckCodePlugin::getInstance()->getLogger();

            $logger->info('§7Começando a salvar as configurações em cache...');
            $startAt = microtime(true);

            foreach (self::$cache as $data) {
                $task = new DataSaveTaskAsync($data->getFilePath(), $data->getContents(), $data->getSaveEngine());
                Server::getInstance()->getScheduler()->scheduleAsyncTask($task);
                self::$taskList[] = $task;
            }

            $lastDiff = null;

            while (
            $count = count(
                array_filter(self::$taskList, function (DataSaveTaskAsync $task) {
                    return !$task->hasResult();
                })
            )) {
                $diff = count(self::$taskList) - $count;
                if($lastDiff != null && $diff - $lastDiff >= 20) {
                    $logger->info('§7Salvando configurações [§f'.$diff.'§7/'.count(self::$taskList).'§7]');
                    $lastDiff = $diff;
                    continue;
                }
                $lastDiff = $diff;
            }

            $endTime = number_format(microtime(true) - $startAt, 2);

            $logger->info('§7Configurações salvas! §f(' . $endTime . 's)');

            self::$taskList = [];
            self::$cache = [];
        }
    }
}