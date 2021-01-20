<?php

declare(strict_types=1);

namespace luckCode\utils\text;

final class FontInfo {

    const CHARACTERS = [
        'A' => 5,
        'a' => 5,
        'B' => 5,
        'b' => 5,
        'C' => 5,
        'c' => 5,
        'D' => 5,
        'd' => 5,
        'E' => 5,
        'F' => 5,
        'f' => 4,
        'G' => 5,
        'g' => 5,
        'H' => 5,
        'h' => 5,
        'I' => 3,
        'i' => 1,
        'J' => 5,
        'j' => 5,
        'K' => 5,
        'k' => 4,
        'L' => 5,
        'l' => 1,
        'M' => 5,
        'm' => 5,
        'N' => 5,
        'n' => 5,
        'O' => 5,
        'o' => 5,
        'P' => 5,
        'p' => 5,
        'Q' => 5,
        'q' => 5,
        'R' => 5,
        'r' => 5,
        'S' => 5,
        'T' => 5,
        't' => 4,
        'U' => 5,
        'u' => 5,
        'V' => 5,
        'v' => 5,
        'W' => 5,
        'w' => 5,
        'X' => 5,
        'x' => 5,
        'Y' => 5,
        'y' => 5,
        'Z' => 5,
        'z' => 5,
        1 => 5,
        2 => 5,
        3 => 5,
        4 => 5,
        5 => 5,
        6 => 5,
        7 => 5,
        8 => 5,
        9 => 5,
        0 => 5,
        '!' => 1,
        '@' => 6,
        '#' => 5,
        '$' => 5,
        '%' => 5,
        '^' => 5,
        '&' => 5,
        '*' => 5,
        '(' => 4,
        ')' => 4,
        '-' => 5,
        '_' => 5,
        '+' => 5,
        '=' => 5,
        '{' => 4,
        '}' => 4,
        '[' => 3,
        ']' => 3,
        ':' => 1,
        ';' => 2,
        '"' => 3,
        '\'' => 3,
        '<' => 5,
        '>' => 5,
        '?' => 5,
        '/' => 5,
        '\\' => 5,
        '|' => 2,
        '~' => 5,
        '`' => 2,
        '´' => 2,
        '.' => 2,
        ',' => 2,
        ' ' => 3,
    ];

    /**
     * @param string $c
     * @return int
     */
    public static function getLength(string $c): int {
        return self::CHARACTERS[$c] ?? 4;
    }

    /**
     * @param string $c
     * @return int
     */
    public static function getBoldLength(string $c): int {
        return (self::CHARACTERS[$c] ?? 4) + 2;
    }
}