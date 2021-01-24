<?php

declare(strict_types=1);

namespace luckCode\menu\controller;

use luckCode\menu\MenuBase;
use pocketmine\Player;
use function array_filter;
use function array_values;
use function spl_object_hash;

final class MenuController {


    /** @var MenuBase $cache */
    private static $cache = [];

    /** @param MenuBase $menu */
    public static function put(MenuBase $menu) {
        self::$cache[spl_object_hash($menu)] = $menu;
    }

    /**
     * @param MenuBase $menu
     * @return bool
     */
    public static function has(MenuBase $menu) : bool {
        return isset(self::$cache[spl_object_hash($menu)]);
    }

    /**
     * @param Player $player
     * @return bool
     */
    public static function hasByPlayer(Player $player) : bool {
        return (bool) self::getByPlayer($player);
    }

    /**
     * @param Player $player
     * @return MenuBase|null
     */
    public static function getByPlayer(Player $player) {
        return array_values(
            array_filter(self::$cache, function (MenuBase $menu) use($player){
                return $player->getWindowId($menu) != -1;
            })
            )[0] ?? null;
    }

    /** @param MenuBase $menu */
    public static function unset(MenuBase $menu) {
        unset(self::$cache[spl_object_hash($menu)]);
    }

    /** @param string $reason */
    public static function closeAll(string $reason) {
        array_walk(self::$cache, function(MenuBase $base) use($reason){
            $viewers = $base->getViewers();
            array_walk($viewers, function(Player $p) use($base, $reason)) {
                $p->removeWindow($base);
                $p->sendMessage($reason);
            }
        });
    }
}