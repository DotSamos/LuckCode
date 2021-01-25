<?php

declare(strict_types=1);

namespace luckCode\data\save\engines;

class JsonSaveFileEngine implements IFileSaveEngine {

    /**
     * @return string
     */
    public function getName(): string {
        return 'Json';
    }

    /**
     * @param string $filePath
     * @param array $contents
     * @return bool
     */
    public function save(string $filePath, array $contents): bool {
        $content = json_encode($contents, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING);
        return file_put_contents($filePath, $content, 0) !== false;
    }
}