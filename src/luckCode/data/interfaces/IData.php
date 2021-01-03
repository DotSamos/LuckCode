<?php

declare(strict_types=1);

namespace luckCode\data\interfaces;

interface IData
{

    /** @return string */
    public function getTypeData() : string;

    /** @return string */
    public function getTypeFile() : string;

    /** @return string */
    public function getFileName() : string;

    /** @return string */
    public function getFilePath() : string;

    public function load();

    public function reload();

    /** @return array */
    public function getContents() : array;

    /** @param array $data */
    public function setContents(array $data);

    /**
     * @param string $key
     * @return bool
     */
    public function hasKey(string $key) : bool;

    /**
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, $value);

    /**
     * @param string $key
     * @param mixed|null $defaultReturn
     * @return mixed|null
     */
    public function get(string $key, $defaultReturn = null);

    /**
     * @param string $route
     * @param mixed|null $defaultReturn
     * @return mixed|null
     */
    public function getByRoute(string $route, $defaultReturn = null);

    /**
     * @param string $key
     * @return bool
     */
    public function remove(string $key) : bool;

    /** @return string */
    public function getSaveEngine() : string;

    /** @return bool */
    public function forceSave() : bool;

    /** @return bool */
    public function isInSaveWorker() : bool;

    /** @return bool */
    public function addInSaveWorker() : bool;
}