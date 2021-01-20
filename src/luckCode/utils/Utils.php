<?php

declare(strict_types=1);

namespace luckCode\utils;

use Throwable;
use function implode;
use function is_numeric;
use function str_replace;
use function strpos;
use function substr;
use function wordwrap;

final class Utils {

    /**
     * @param Throwable $error
     * @return string
     */
    public static function getThrowablePrint(Throwable $error) : string {
        $pos = strpos($error->getFile(), 'luckCode');
        return implode("\n", [
            "§8",
            '§c| §4(' . $error->getCode() . ') §7' . wordwrap(str_replace("\n", '', $error->getMessage()), 80, "\n§c|§7 "),
            "§c| §7Line §f{$error->getLine()}§7 from:",
            "§c| §b" . wordwrap(is_numeric($pos) ? substr($error->getFile(), $pos) : $error->getFile(), 80, "\n§c|§7 ")
        ]);
    }
}