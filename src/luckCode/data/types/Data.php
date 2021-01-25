<?php

declare(strict_types=1);

namespace luckCode\data\types;

use luckCode\data\interfaces\IData;
use luckCode\data\save\engines\IFileSaveEngine;
use luckCode\data\save\manager\DataSaveWorker;
use pocketmine\plugin\PluginBase;

abstract class Data implements IData {

    /** @var string $file */
    protected $file;

    /** @var string $filePath */
    protected $filePath;

    /** @var PluginBase $pluginOwner */
    protected $pluginOwner;

    /** @var array $data */
    protected $data = [];

    function __construct(string $file, string $filePath, PluginBase $plugin) {
        $file = $file . '.' . $this->getTypeFile();
        $this->file = $file;

        if (!is_dir($filePath)) mkdir($filePath);

        $this->filePath = $filePath . DIRECTORY_SEPARATOR . $file;
        $this->pluginOwner = $plugin;

        $plugin->saveResource($file);
        $this->load();
    }

    public function reload() {
        $this->data = [];
        $this->load();
    }

    /** @return string */
    public function getFilePath(): string {
        return $this->filePath;
    }

    /** @return mixed[] */
    public function getContents(): array {
        return $this->data;
    }

    /** @param mixed[] */
    public function setContents(array $data) {
        $this->data = $data;
    }

    /** @return string */
    public function getFileName(): string {
        return explode('.', $this->file)[0];
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, $value) {
        $this->data[$key] = $value;
    }

    /**
     * @param string $key
     * @param null $defaultReturn
     * @return mixed
     */
    public function get(string $key, $defaultReturn = null) {
        return $this->data[$key] ?? $defaultReturn;
    }

    /**
     * @param string $route
     * @param null $defaultReturn
     * @return mixed
     */
    public function getByRoute(string $route, $defaultReturn = null) {
        $vars = explode('.', $route);
        $base = array_shift($vars);
        if (isset($this->data[$base])) {
            $base = $this->data[$base];
        } else {
            return $defaultReturn;
        }

        while (count($vars) > 0) {
            $baseKey = array_shift($vars);
            if (is_array($base) and isset($base[$baseKey])) {
                $base = $base[$baseKey];
            } else {
                return $defaultReturn;
            }
        }
        return $base;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function remove(string $key): bool {
        if ($this->hasKey($key)) {
            unset($this->data[$key]);
            return true;
        }
        return false;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasKey(string $key): bool {
        return isset($this->data[$key]);
    }

    /** @return bool */
    public function forceSave(): bool {
        $engine = $this->getSaveEngine();
        /** @var IFileSaveEngine $engineWriter */
        $engineWriter = new $engine();
        return $engineWriter->save($this->filePath, $this->data);
    }

    /** @return bool */
    public function isInSaveWorker(): bool {
        return DataSaveWorker::contains($this);
    }

    /** @return bool */
    public function addInSaveWorker(): bool {
        return DataSaveWorker::put($this);
    }
}