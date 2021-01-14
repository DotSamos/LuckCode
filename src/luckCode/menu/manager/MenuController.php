<?php

declare(strict_types=1);

namespace luckCode\menu\manager;

use luckCode\menu\Menu;
use pocketmine\Player;

final class MenuController
{

    /** @var Menu[] $cache */
    private static $cache = [];

    /** @return Menu[]|[] */
    public static function getAll(): array
    {
        return self::$cache;
    }

    /** @param string $reason */
    public static function closeAll(string $reason)
    {
        array_walk(self::$cache, function (Menu $menu) use ($reason) {
            $viewers = $menu->getViewers();
            array_walk($viewers, function (Player $p) use ($reason, $menu) {
                $p->removeWindow($menu);
                $p->sendMessage($reason);
            });
        });
    }

    /**
     * @param Player $p
     * @param Menu $menu
     * @return bool
     */
    public static function put(Player $p, Menu $menu): bool
    {
        if (!self::has($p)) {
            self::$cache[spl_object_hash($p)] = $menu;
            return true;
        }
        return false;
    }

    /**
     * @param Player $p
     * @return bool
     */
    public static function has(Player $p): bool
    {
        return isset(self::$cache[spl_object_hash($p)]);
    }

    /**
     * @param Player $p
     * @return bool
     */
    public static function remove(Player $p): bool
    {
        if (self::has($p)) {
            unset(self::$cache[spl_object_hash($p)]);
            return true;
        }
        return false;
    }
}
