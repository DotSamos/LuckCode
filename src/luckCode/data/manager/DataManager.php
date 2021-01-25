<?php

declare(strict_types=1);

namespace luckCode\data\manager;

use luckCode\data\interfaces\IData;

abstract class DataManager implements IDataManager {

    /** @var IData[] $cache */
    protected $cache = [];

    public function __construct() {
        $this->loadDefaults();
    }

    /**
     * @param string $file
     * @return IData|null
     */
    public function get(string $file) {
        return $this->cache[$file] ?? null;
    }

    /**
     * @param IData $data
     * @return bool
     */
    public function put(IData $data): bool {
        if (!$this->contains($name = $data->getFileName())) {
            $this->cache[$name] = $data;
            return true;
        }
        return false;
    }

    /**
     * @param string $file
     * @return bool
     */
    public function contains(string $file): bool {
        return isset($this->cache[$file]);
    }

    /**
     * @param string $file
     * @return bool
     */
    public function remove(string $file): bool {
        if ($this->contains($file)) {
            unset($this->cache[$file]);
        }
        return false;
    }

    public function putAllInSaveWorker() {
        array_walk($this->cache, function (IData $data) {
            $data->addInSaveWorker();
        });
    }

    public function forceSaveAll() {
        array_walk($this->cache, function (IData $data) {
            $data->forceSave();
        });
    }
}