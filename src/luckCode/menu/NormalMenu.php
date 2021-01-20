<?php

declare(strict_types=1);

namespace luckCode\menu;

use Exception;
use pocketmine\block\Block;
use pocketmine\inventory\InventoryType;
use pocketmine\level\Position;
use pocketmine\Player;

abstract class NormalMenu extends Menu {

    /**
     * NormalMenu constructor.
     * @param Player $p
     * @param string $name
     * @throws Exception
     */
    public function __construct(Player $p, string $name) {
        if (!$p->isCreative()) {
            $level = $p->level;
            $pos = $p->getPosition()->floor();
            if ($pos->y <= 0 || $pos->y >= 127) {
                throw new Exception(self::BAD_POSITION);
            }
            $diff = -2;
            $pos = $pos->add(0, $diff, 0);
            if ($level->getTile($pos)) {
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
    public function onOpen(Player $who) {
        if (!$who->isCreative()) {
            parent::onOpen($who);
            $this->setItems($this->getItems($who));
        } else {
            throw new Exception(self::IS_CREATIVE);
        }
    }

    public function onClose(Player $who) {
        $pos = $this->position;
        $realBlock = $pos->level->getBlock($pos);
        $this->sendBlock($who, $realBlock);
        parent::onClose($who);
    }
}
