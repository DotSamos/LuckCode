<?php

declare(strict_types=1);

namespace luckCode\data\save\manager;

use luckCode\data\interfaces\IData;
use luckCode\data\save\task\DataSaveTaskAsync;
use pocketmine\Server;

class DataSaveWorker
{

    /** @var IData[] $cache */
    private static $cache = [];

    /** @var string[] $successWorked */
    public static $successWorked = [];

    /**
     * @param IData $data
     * @return bool
     */
    public static function contains(IData $data): bool
    {
        return isset(self::$cache[spl_object_hash($data)]);
    }

    /**
     * @param IData $data
     * @return bool
     */
    public static function put(IData $data): bool
    {
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
    public static function remove(IData $data): bool
    {
        if (!self::contains($data)) {
            unset(self::$cache[spl_object_hash($data)]);
            return true;
        }
        return false;
    }

    public static function startWorker()
    {
        array_walk(self::$cache, function (IData $data) {
            Server::getInstance()->getScheduler()->scheduleAsyncTask(
                new DataSaveTaskAsync($data->getFilePath(), $data->getContents(), $data->getSaveEngine())
            );
        });
    }
}