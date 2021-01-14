<?php

namespace luckCode\data\types;

use luckCode\data\save\engines\JsonSaveFileEngine;

class JsonData extends Data
{

    /**
     * @inheritDoc
     */
    public function getTypeData(): string
    {
        return 'Json';
    }

    /**
     * @inheritDoc
     */
    public function getTypeFile(): string
    {
        return 'json';
    }

    public function load()
    {
        $filePath = $this->filePath;
        if (file_exists($filePath)) {
            $contents = file_get_contents($filePath);
            $this->data = json_decode($contents, true);
        } else {
            $this->forceSave();
        }
    }

    /**
     * @inheritDoc
     */
    public function getSaveEngine(): string
    {
        return JsonSaveFileEngine::class;
    }
}