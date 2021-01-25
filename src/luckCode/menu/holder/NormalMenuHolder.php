<?php

declare(strict_types=1);

namespace luckCode\menu\holder;

use luckCode\menu\NormalMenu;
use pocketmine\block\Block;
use pocketmine\inventory\InventoryType;
use pocketmine\level\Position;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\protocol\BlockEntityDataPacket;
use pocketmine\Player;
use pocketmine\tile\Tile;

class NormalMenuHolder extends BaseMenuHolder {

    /** @return InventoryType */
    public function getInventoryType(): InventoryType {
        return InventoryType::get(InventoryType::CHEST);
    }

    /**
     * @param Position $position
     * @return NBT
     */
    public function getBaseSpawnTileNBT(Position $position): NBT {
        $compound = new CompoundTag('', [
            new IntTag('x', $this->x),
            new IntTag('y', $this->x),
            new IntTag('z', $this->x),
            new StringTag('id', Tile::CHEST),
            new StringTag('CustomName', $this->getCustomName())
        ]);
        $nbt = new NBT();
        $nbt->setData($compound);
        return $nbt;
    }

    /** @param Player[]|Player|null $player*/
    public function spawnTo($player = null) {
        $player = $player ?? $this->handler;
        if($player) {
            $chestBlock = Block::get(Block::CHEST);
            $chestBlock->position($this);
            $this->sendBlocks([$chestBlock], $player);

            $pk = new BlockEntityDataPacket();
            $pk->x = $this->x;
            $pk->y = $this->y;
            $pk->z = $this->z;
            $pk->namedtag = $this->getBaseSpawnTileNBT($this)->write();
            $this->sendPackets([$pk], $player);
        }
    }
}