<?php

declare(strict_types=1);

namespace luckCode\menu\interfaces;

use pocketmine\block\Block;
use pocketmine\inventory\CustomInventory;
use pocketmine\inventory\InventoryType;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\network\protocol\DataPacket;
use pocketmine\Player;
use pocketmine\tile\Chest;

interface IMenuHolder {

    public function getHandler() : Player;

    public function getCustomName() : string;

    /** @return Item[] */
    public function getBaseItems() : array;

    /** @return CustomInventory */
    public function getInventory() : CustomInventory;
    public function getTile() : Chest;
    public function getInventoryType() : InventoryType;

    /** @return Chest|null */
    public function getOldTile();

    public function getX() : int;
    public function getY() : int;
    public function getZ() : int;
    public function getLevel() : Level;

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