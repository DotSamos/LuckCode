<?php

namespace luckCode\data\types;

use luckCode\data\save\engines\YamlSaveFileEngine;
use pocketmine\utils\Config;

class YamlData extends Data
{

    /**
     * @inheritDoc
     */
    public function getTypeData(): string
    {
        return 'Yaml';
    }

    /**
     * @inheritDoc
     */
    public function getTypeFile(): string
    {
        return 'yml';
    }

    public function load()
    {
        $filePath = $this->filePath;
        if (file_exists($filePath)) {
            $content = Config::fixYAMLIndexes(file_get_contents($filePath));
            $this->data = yaml_parse($content);
        } else {
            $this->forceSave();
        }
    }

    /**
     * @inheritDoc
     */
    public function getSaveEngine(): string
    {
        return YamlSaveFileEngine::class;
    }
}