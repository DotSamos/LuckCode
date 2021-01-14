<?php

declare(strict_types=1);

namespace luckCode\entity;

use luckCode\entity\holographic\LuckHolographicEntity;
use pocketmine\entity\Entity;
use pocketmine\level\Location;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\nbt\tag\StringTag;
use function array_walk;

final class EntityManager
{

    const DEFAULTS = [LuckHolographicEntity::class];

    public static function registerDefaults()
    {
        $all = self::DEFAULTS;
        array_walk($all, function (string $string) {
            Entity::registerEntity($string, true);
        });
    }

    /**
     * @param Location $loc
     * @param string|null $nameTag
     * @return CompoundTag
     */
    public static function getBaseSpawnCompound(Location $loc, string $nameTag = null): CompoundTag
    {
        $nbt = new CompoundTag('', [
            'Pos' => new ListTag('Pos', [
                new DoubleTag('', $loc->x),
                new DoubleTag('', $loc->y),
                new DoubleTag('', $loc->z)
            ]),
            'Rotation' => new ListTag('Rotation', [
                new FloatTag('', $loc->yaw),
                new FloatTag('', $loc->pitch)
            ]),
            'Health' => new ShortTag('Health', 1),
        ]);
        if($nameTag != null) {
            $nbt->CustomNameVisible = new ByteTag('CustomNameVisible', 1);
            $nbt->CustomName = new StringTag('CustomName', $nameTag);
        }
        return $nbt;
    }
}