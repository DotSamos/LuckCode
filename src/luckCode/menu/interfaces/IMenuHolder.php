<?php

declare(strict_types=1);

namespace luckCode\menu\interfaces;

use pocketmine\block\Block;
use pocketmine\inventory\CustomInventory;
use pocketmine\inventory\InventoryType;
use pocketmine\level\Position;
use pocketmine\nbt\NBT;
use pocketmine\network\protocol\DataPacket;
use pocketmine\Player;
use pocketmine\tile\Tile;

interface IMenuHolder {

    /** @return Player|null */
    public function getHandler();

    /** @param Player $handler */
    public function setHandler(Player $handler);

    /** @return bool */
    public function canAddItems() : bool;

    public function getCustomName() : string;

    /** @return CustomInventory */
    public function getInventory() : CustomInventory;
    public function getInventoryType() : InventoryType;
    public function getMenuClass() : string;

    /** @return Tile|null */
    public function getOldTile();

    /**
     * @param Position $position
     * @return NBT
     */
    public function getBaseSpawnTileNBT(Position $position) : NBT;

    /**
     * @param Player[]|Player|null $player
     */
    public function spawnTo($player = null);

    /**
     * @param Player[]|Player|null $player
     */
    public function removeTo($player = null);

    /**
     * @param DataPacket[]|DataPacket $pks
     * @param Player[]|Player|null $player
     */
    public function sendPackets($pks, $player = null);

    /**
     * @param Block[] $blocks
     * @param Player[]|Player|null $player
     */
    public function sendBlocks(array $blocks, $player = null);
}