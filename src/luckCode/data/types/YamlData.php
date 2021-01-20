<?php

namespace luckCode\data\types;

use luckCode\data\save\engines\YamlSaveFileEngine;
use pocketmine\utils\Config;

class YamlData extends Data {

    /** @return string */
    public function getTypeData(): string {
        return 'Yaml';
    }

    /** @return string */
    public function getTypeFile(): string {
        return 'yml';
    }

    public function load() {
        $filePath = $this->filePath;
        if (file_exists($filePath)) {
            $content = Config::fixYAMLIndexes(file_get_contents($filePath));
            $this->data = yaml_parse($content);
        }
    }

    /** @return string */
    public function getSaveEngine(): string {
        return YamlSaveFileEngine::class;
    }
}