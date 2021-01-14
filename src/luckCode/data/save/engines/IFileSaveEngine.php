<?php

declare(strict_types=1);

namespace luckCode\data\save\engines;

interface IFileSaveEngine
{
    /** @return string */
    public function getName(): string;

    /**
     * @param string $filePath
     * @param array $contents
     * @return bool
     */
    public function save(string $filePath, array $contents): bool;
}