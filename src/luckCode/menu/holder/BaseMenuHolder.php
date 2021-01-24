<?php

declare(strict_types=1);

namespace luckCode\menu\holder;

use Exception;
use function array_walk;
use function is_array;
use luckCode\LuckCodePlugin;
use luckCode\menu\interfaces\IMenuHolder;
use pocketmine\Player;
use pocketmine\block\Block;
use pocketmine\inventory\CustomInventory;
use pocketmine\inventory\InventoryHolder;
use pocketmine\level\Position;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\network\protocol\BlockEntityDataPacket;
use pocketmine\network\protocol\DataPacket;
use pocketmine\network\protocol\UpdateBlockPacket;
use pocketmine\tile\Spawnable;
use pocketmine\tile\Tile;

abstract class BaseMenuHolder extends Position implements IMenuHolder, InventoryHolder {

    /** @var string $customName */
    protected $customName;

    /** @var Player|null $handler */
    protected $handler;

    /** @var CustomInventory $inventory */
    protected $inventory;

    /** @var Tile $holdTile */
    protected $holdTile;

    /** @var string $menuClass */
    protected $menuClass;

    /**
     * BaseMenuHolder constructor.
     * @param Position $pos
     * @param string $menuClass
     * @param string|null $customName
     * @throws Exception
     */
    public function __construct(Position $pos, string $menuClass, string $customName = null) {
        $floor = $pos->floor();
        parent::__construct((int)$floor->x, ($y = (int)($floor->y - 3)), (int)$floor->z, $pos->level);

        if($y >= 127 || $y <= 0) {
            throw new Exception('O menu apenas pode ser aberto entre as camadas 0 - 127!');
        } else if($pos instanceof Player) {
            if($pos->isCreative()) {
                throw new Exception('Você não pode abrir o menu no modo criativo!');
            } else {
                $pos->getFloatingInventory()->clearAll(false);
                $this->handler = $pos;
            }
        }
        $this->customName = $customName;

        $this->holdTile = $pos->level->getTile($pos);

        $this->menuClass = $menuClass;
    }

    /** @return Player|null */
    public function getHandler() {
        return $this->handler;
    }

    /** @return bool */
    public function canAddItems(): bool {
        return false;
    }

    /** @param Player $handler */
    public function setHandler(Player $handler) {
        $this->handler = $handler;
    }

    /** @return string */
    public function getCustomName(): string {
        return $this->customName ?? '§3Luck§l§5Code§r§7 v' . LuckCodePlugin::VERSION;
    }

    /** @return CustomInventory */
    public function getInventory(): CustomInventory {
        $class = $this->getMenuClass();
        if (!$this->inventory) {
            $this->inventory = new $class($this, $this->getInventoryType());
        }
        return $this->inventory;
    }

    /** @return string */
    public function getMenuClass(): string {
        return $this->menuClass;
    }

    /** @return Tile|null */
    public function getOldTile() {
        return $this->holdTile;
    }

    /** @param Player[]|Player|null */
    public function removeTo($player = null) {
        $realBlock = $this->level->getBlock($this);
        $this->sendBlocks([$realBlock], $player);
        
        $oldTile = $this->holdTile;
        if ($oldTile instanceof Spawnable) {
            if (is_array($player)) {
                array_walk($player, function (Player $p) use ($oldTile) {
                    $oldTile->spawnTo($p);
                });
            } else if ($player || $this->handler) {
                $oldTile->spawnTo($player ?? $this->handler);
            }
        } else if($player || $this->handler) {
            $this->removeFakeTileAt($this, $player ?? $this->handler);
        }
    }

    /**
     * @param Position $pos
     * @param Player $player
     */
    protected function removeFakeTileAt(Position $pos, Player $player) {
        $nbt = new NBT();
        $nbt->setData(
            new CompoundTag('', [
            new IntTag('x', $pos->x),
            new IntTag('y', $pos->y),
            new IntTag('z', $pos->z)
        ]));
        $pk = new BlockEntityDataPacket();
        $pk->x = $pos->x;
        $pk->y = $pos->y;
        $pk->z = $pos->z;
        $pk->namedtag = $nbt->write();
        $this->sendPackets([$pk], $player);
    }

    /**
     * @param DataPacket|DataPacket[] $pks
     * @param Player[]|Player|null $player
     */
    public function sendPackets($pks, $player = null) {
        if (is_array($player)) {
            array_walk($player, function (Player $p) use ($pks) {
                $this->sendPackets($pks, $p);
            });
        } else if ($player || $this->handler) {
            $player = $player ?? $this->handler;
            array_walk($pks, function (DataPacket $pk) use ($player) {
                $player->dataPacket($pk);
            });
        }
    }

    /**
     * @param Block[] $blocks
     * @param Player[]|Player|null $player
     */
    public function sendBlocks(array $blocks, $player = null) {
        $pks = [];

        $pk = new UpdateBlockPacket();

        array_walk($blocks, function (Block $block) use (&$pks, $pk) {
            $pk->blockId = $block->getId();
            $pk->blockData = $block->getDamage();
            $pk->x = $block->x;
            $pk->y = $block->y;
            $pk->z = $block->z;
            $pk->flags = $pk::FLAG_ALL;
            $pks[] = clone $pk;
        });
        $this->sendPackets($pks, $player);
    }
}