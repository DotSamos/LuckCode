<?php

namespace luckCode\data\types;

use luckCode\data\save\engines\JsonSaveFileEngine;

class JsonData extends Data {

    /** @return string */
    public function getTypeData(): string {
        return 'Json';
    }

    /** @return string */
    public function getTypeFile(): string {
        return 'json';
    }

    public function load() {
        $filePath = $this->filePath;
        if (file_exists($filePath)) {
            $contents = file_get_contents($filePath);
            $this->data = json_decode($contents, true);
        }
    }

    /** @return string */
    public function getSaveEngine(): string {
        return JsonSaveFileEngine::class;
    }
}