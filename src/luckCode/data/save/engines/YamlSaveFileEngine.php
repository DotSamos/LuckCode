<?php

declare(strict_types=1);

namespace luckCode\data\save\engines;

class YamlSaveFileEngine implements IFileSaveEngine
{

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'Yaml';
    }

    /**
     * @inheritDoc
     */
    public function save(string $filePath, array $contents): bool
    {
        $content = yaml_emit($contents, YAML_UTF8_ENCODING);
        return file_put_contents($filePath, $content, 0) !== false;
    }
}