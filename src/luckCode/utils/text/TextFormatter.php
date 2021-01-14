<?php

declare(strict_types=1);

namespace luckCode\utils\text;

use pocketmine\utils\TextFormat;
use function array_map;
use function array_sum;
use function array_values;
use function array_walk;
use function explode;
use function implode;
use function max;
use function str_repeat;
use function strlen;
use function strpos;

final class TextFormatter
{
    /**
     * @param string $message
     * @return string
     */
    public static function center(string $message): string
    {
        $parts = explode("\n", TextFormat::clean($message));
        $bkParts = explode("\n", $message);
        $finalText = [];

        $partKey = 0;

        $maxLengthPart = max(array_values(array_map(function (string $string) use ($bkParts, &$partKey) {
            $defaultPart = $bkParts[$partKey];
            $partKey++;
            $boldIn = strpos($defaultPart, "§l");
            $resetAt = strpos($defaultPart, "§r") ?? strlen($string);

            $cKey = 0;

            return array_sum(array_map(function (string $c) use ($boldIn, $resetAt, &$cKey) {
                $length = FontInfo::getLength($c) + ($boldIn >= $cKey && $cKey < $resetAt ? 1 : 0);
                $cKey++;
                return $length;
            }, self::stringToArrayChars($string)));
        }, $parts)));

        foreach ($parts as $k => $v) {
            $boldIn = strpos($bkParts[$k], "§l");
            $resetAt = strpos($bkParts[$k], "§r") ?? strlen($v);

            $cKey = 0;

            $length = array_sum(array_map(function (string $c) use ($boldIn, $resetAt, &$cKey) {
                $length = FontInfo::getLength($c) + ($boldIn >= $cKey && $cKey < $resetAt ? 1 : 0);
                $cKey++;
                return $length;
            }, self::stringToArrayChars($v)));
            $diff = (int)((($maxLengthPart - $length) / 7));

            $finalText[] = str_repeat(' ', $diff) . $bkParts[$k];
        }
        return implode("\n", $finalText);
    }

    /**
     * @param string $message
     * @return array
     */
    private static function stringToArrayChars(string $message): array
    {
        $final = [];
        $parts = explode(' ', $message);
        array_walk($parts, function (string $string) use (&$final) {
            for ($i = 0; $i < strlen($string); $i++) {
                $final[] = $string[$i];
            }
        });
        return $final;
    }
}