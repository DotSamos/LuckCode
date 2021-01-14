<?php

declare(strict_types=1);

namespace luckCode\data\manager;

use luckCode\data\interfaces\IData;

interface IDataManager
{

    public function loadDefaults();

    /**
     * @param string $file
     * @return bool
     */
    public function contains(string $file): bool;

    /**
     * @param string $file
     * @return IData|null
     */
    public function get(string $file);

    /**
     * @param IData $data
     * @return bool
     */
    public function put(IData $data): bool;

    /**
     * @param string $file
     * @return bool
     */
    public function remove(string $file): bool;

    public function putAllInSaveWorker();

    public function forceSaveAll();
}