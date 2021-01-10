<?php

declare(strict_types=1);

namespace luckCode\menu\tile;

use pocketmine\level\format\FullChunk;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Player;
use pocketmine\tile\Chest;

class MenuChestTile extends Chest
{

    /**
     * MenuChestTile constructor.
     * @param FullChunk $chunk
     * @param CompoundTag $nbt
     * @param Player|null $p
     */
    public function __construct(FullChunk $chunk, CompoundTag $nbt, Player $p = null)
    {
        parent::__construct($chunk, $nbt);
        $this->spawnTo($p);
    }

    public function saveNBT()
    {}

    public function spawnToAll()
    {}
}