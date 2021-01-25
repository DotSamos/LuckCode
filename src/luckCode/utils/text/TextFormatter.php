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
use function var_dump;

final class TextFormatter {

    /**
     * @param string $message
     * @return string
     */
    public static function center(string $message): string {
        $parts = explode("\n", TextFormat::clean($message));
        $bkParts = explode("\n", $message);
        $finalText = [];

        $maxLengthPart = max(array_map(function (string $part){
            return self::getCharsLength($part);
        }, $parts));

        foreach ($parts as $k => $v) {
            $boldIn = strpos($bkParts[$k], "§l");
            $resetAt = strpos($bkParts[$k], "§r") ?? strlen($v);

            #echo var_dump(['frase' => $v, 'bold' => $boldIn, 'reset' => $resetAt]);

            $length = self::getCharsLength($v);
            $diff = (int)((($maxLengthPart - $length) / 7));

            $finalText[] = str_repeat(' ', $diff) . $bkParts[$k];
        }
        return implode("\n", $finalText);
    }

    /**
     * @param string $string
     * @return int
     */
    public static function getCharsLength(string $string) : int {
        $boldPos = strpos($string, "§l");
        $resetPos = strpos($string, "§r");
        $charKey = 0;
        return array_sum(
            array_map(function (string $char) use($boldPos, $resetPos, &$charKey){
                $length = ($boldPos != false && $charKey >= $boldPos) && !($resetPos != false && $charKey < $resetPos) ? FontInfo::getBoldLength($char) : FontInfo::getLength($char);
                $charKey++;
                return $length;
            }, self::stringToArrayChars($string))
        );
    }

    /**
     * @param string $message
     * @return array
     */
    private static function stringToArrayChars(string $message): array {
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