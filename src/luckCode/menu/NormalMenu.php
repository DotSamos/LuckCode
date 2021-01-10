<?php

declare(strict_types=1);

namespace luckCode\menu;

use Exception;
use luckCode\LuckCodePlugin;
use luckCode\menu\interfaces\IMenu;
use luckCode\menu\tile\MenuChestTile;
use pocketmine\block\Block;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\Listener;
use pocketmine\inventory\ContainerInventory;
use pocketmine\inventory\InventoryType;
use pocketmine\level\Position;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\protocol\UpdateBlockPacket;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\tile\Tile;

abstract class NormalMenu extends Menu
{

    /**
     * NormalMenu constructor.
     * @param Player $p
     * @param string $name
     * @throws Exception
     */
    public function __construct(Player $p, string $name)
    {
        if(!$p->isCreative()) {
            $level = $p->level;
            $pos = $p->getPosition()->floor();
            if($pos->y <= 0) {
                throw new Exception(self::BAD_POSITION);
            }
            if($pos->y+2 > 127) {
                $diff = -2;
            } else {
                $diff = 2;
            }
            $pos = $pos->add(0, $diff, 0);
            if($level->getTile($pos)) {
                throw  new Exception(self::HAS_TILE);
            }
            $this->position = $pos = new Position($pos->x, $pos->y, $pos->z, $level);
            $this->sendBlock($p, Block::get(Block::CHEST, 0, $pos));
            $holder = $this->makeTile($pos, $p, $name);
            parent::__construct($holder, InventoryType::get(InventoryType::CHEST), [], null, 'MenuChestTile');
        } else {
            throw new Exception(self::IS_CREATIVE);
        }
    }

    /**
     * @param Player $who
     * @throws Exception
     */
    public function onOpen(Player $who)
    {
        if(!$who->isCreative()) {
            parent::onOpen($who);
            $this->setItems($this->getItems($who));
        } else {
            throw new Exception(self::IS_CREATIVE);
        }
    }

    public function onClose(Player $who)
    {
        parent::onClose($who);
        $pos = $this->position;
        $realBlock = $pos->level->getBlock($pos);
        $this->sendBlock($who, $realBlock);
    }
}