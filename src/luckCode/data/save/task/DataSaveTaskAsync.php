<?php

declare(strict_types=1);

namespace luckCode\data\save\task;

use luckCode\data\save\engines\IFileSaveEngine;
use pocketmine\scheduler\AsyncTask;

class DataSaveTaskAsync extends AsyncTask {

    /** @var string $filePath */
    private $filePath;

    /** @var string $contents */
    private $contents;

    /** @var string $writeEngine */
    private $writeEngine;

    /**
     * DataSaveTaskAsync constructor.
     * @param string $filePath
     * @param array $contents
     * @param string $writeEngine
     */
    public function __construct(string $filePath, array $contents, string $writeEngine) {
        $this->filePath = $filePath;
        $this->contents = serialize($contents);
        $this->writeEngine = $writeEngine;
    }

    public function onRun() {
        /** @var IFileSaveEngine $engineWrite */
        $engineWrite = new $this->writeEngine();
        $this->setResult($engineWrite->save($this->filePath, unserialize($this->contents)), false);
    }
}