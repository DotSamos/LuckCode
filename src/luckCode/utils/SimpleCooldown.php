<?php

declare(strict_types=1);

namespace luckCode\utils;

use function spl_object_hash;

abstract class SimpleCooldown {

    /** @var SimpleCooldown[] $cooldowns */
    private static $cooldowns = [];

    public static function update() {
        foreach (self::$cooldowns as $cooldown) {
            if($cooldown->time-- <= 0) {
                $cooldown->execute();
                unset(self::$cooldowns[array_search($cooldown, self::$cooldowns)]);
            }
        }
    }

    /** @var int $time */
    public $time;

    /**
     * SimpleCooldown constructor.
     * @param int $time
     */
    public function __construct(int $time) {
        $this->time = $time;
        self::$cooldowns[] = $this;
    }

    public abstract function execute();
}