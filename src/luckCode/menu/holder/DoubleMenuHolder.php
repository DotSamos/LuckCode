<?php

declare(strict_types=1);


namespace luckCode\menu\holder;


use function array_walk;
use luckCode\menu\DoubleMenu;
use luckCode\menu\interfaces\IDoubleMenuHolder;
use luckCode\menu\utils\DoubleMenuWindow;
use pocketmine\Player;
use pocketmine\block\Block;
use pocketmine\inventory\ChestInventory;
use pocketmine\inventory\CustomInventory;
use pocketmine\inventory\InventoryType;
use pocketmine\level\Position;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\protocol\BlockEntityDataPacket;
use pocketmine\tile\Tile;

class DoubleMenuHolder extends BaseMenuHolder implements IDoubleMenuHolder {

    /** @var ChestInventory $inventory1 */
    private $inventory1;

    /** @var ChestInventory $inventory2 */
    private $inventory2;

    /** @var Tile $pairOldTile */
    private $pairOldTile;

    public function __construct(Position $pos, string $menuClass, string $customName = null) {
        parent::__construct($pos, $menuClass, $customName);

        $this->inventory1 = new DoubleMenuWindow($this);
        $this->inventory2 = new DoubleMenuWindow($this);

        $this->pairOldTile = $pos->level->getTile($pos);
    }

    /** @return InventoryType */
    public function getInventoryType(): InventoryType {
        return InventoryType::get(InventoryType::CHEST);
    }

    /** @return CustomInventory */
    public function getInventory(): CustomInventory {
        if(!$this->inventory) {
            $this->inventory = new $this->menuClass($this, $this->inventory1, $this->inventory2);
        }
        return $this->inventory;
    }

    /**
     * @param Position $position
     * @return NBT
     */
    public function getBaseSpawnTileNBT(Position $position): NBT {
        $compound = new CompoundTag('', [
            new StringTag('id', Tile::CHEST),
            new IntTag('x', (int)$this->x),
            new IntTag('y', (int)$this->y),
            new IntTag('z', (int)$this->z),
            new IntTag('pairx', (int)$this->getPairX()),
            new IntTag('pairz', (int)$this->getPairZ()),
            new StringTag('CustomName', $this->getCustomName())
        ]);
        $nbt = new NBT();
        $nbt->setData($compound);
        return $nbt;
    }

    /** @param Player[]|Player|null $player */
    public function spawnTo($player = null) {
        $pos = [$this, $this->add(1, 0, 0)];
        $blocks = [];
        $packets = [];
        array_walk($pos, function (Position $position) use($player, &$blocks, &$packets){
           $block = Block::get(Block::CHEST);
           $block->position($position);
           $blocks[] = $block;

           $pk = new BlockEntityDataPacket();
           $pk->x = $this->x;
           $pk->y = $this->y;
           $pk->z = $this->z;
           $pk->namedtag = $this->getBaseSpawnTileNBT($position)->write();
           $packets[] = $pk;
        });

        $this->sendBlocks($blocks, $player);
        $this->sendPackets($packets, $player);
    }

    /** @param Player[]|Player|null $player */
    public function removeTo($player = null) {
        $realBlock = $this->level->getBlock($this->getPairPosition());
        $this->sendBlocks([$realBlock], $player);
        
        $oldTile = $this->pairOldTile;
        if ($oldTile instanceof Spawnable) {
            if (is_array($player)) {
                array_walk($player, function (Player $p) use ($oldTile) {
                    $oldTile->spawnTo($p);
                });
            } else if ($player || $this->handler) {
                $oldTile->spawnTo($player ?? $this->handler);
            }
        } else if($player || $this->handler) {
            $this->removeFakeTileAt($this->getPairPosition(), $player ?? $this->handler);
        }
        parent::removeTo($player);
    }

    /** @return Position */
    public function getPairPosition(): Position {
        return $this->add(1, 0, 0);
    }

    /** @return int */
    public function getPairX(): int {
        return $this->getPairPosition()->x;
    }

    /** @return int */
    public function getPairZ(): int {
        return $this->getPairPosition()->z;
    }

    /** @return DoubleMenuWindow */
    public function getLeft() : DoubleMenuWindow {
        return $this->inventory1;
    }   

    /** @return DoubleMenuWindow */
    public function getRight() : DoubleMenuWindow {
        return $this->inventory2;
    }
}