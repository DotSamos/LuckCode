<?php

declare(strict_types=1);

namespace luckCode\menu;

use Exception;
use luckCode\LuckCodePlugin;
use luckCode\menu\tile\MenuChestTile;
use luckCode\scheduler\LuckTask;
use pocketmine\block\Block;
use pocketmine\block\Chest;
use pocketmine\event\block\BlockUpdateEvent;
use pocketmine\inventory\BaseInventory;
use pocketmine\inventory\ChestInventory;
use pocketmine\inventory\DoubleChestInventory;
use pocketmine\inventory\InventoryType;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\network\protocol\UpdateBlockPacket;
use pocketmine\Player;
use pocketmine\Server;
use function array_walk;

abstract class DoubleMenu extends Menu
{
    /** @var Chest $chest1 */
    private $chest1;

    /** @var Chest $chest2 */
    private $chest2;

    /** @var ChestInventory $left */
    private $left;

    /** @var ChestInventory $right */
    private $right;

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
            if($pos->y <= 0 || $pos->y >= 127) {
                throw new Exception(self::BAD_POSITION);
            }
            $diff = -2;
            $pos = $pos->add(0, $diff, 0);
            $pos2 = $pos->add(1, 0, 0);
            if($level->getTile($pos) || $level->getTile($pos2)) {
                throw new Exception(self::HAS_TILE);
            }

            $this->position = ($pos = new Position($pos->x, $pos->y, $pos->z, $level));
            $pos2 = new Position($pos2->x, $pos2->y, $pos2->z, $level);

            $chest1 = $this->makeChest($p, $pos, $name, 1);
            $chest2 = $this->makeChest($p, $pos2, $name, 2);

            $chest1->pairWith($chest2);
            $chest2->pairWith($chest1);
            $chest1->spawnTo($p);
            $chest2->spawnTo($p);

            $this->left = $chest1->getRealInventory();
            $this->right = $chest2->getRealInventory();

            parent::__construct($chest1, InventoryType::get(InventoryType::DOUBLE_CHEST), [], null);
        } else {
            throw new Exception(self::IS_CREATIVE);
        }
    }

    /**
     * @param Player $p
     * @param Position $pos
     * @param string $name
     * @param int $id
     * @return MenuChestTile
     */
    private function makeChest(Player $p, Position $pos, string $name, int $id) : MenuChestTile {
        $this->sendBlock($p, $block = Block::get(Block::CHEST, 0, $pos));
        $this->{'chest'.$id} = $block;
        return $this->makeTile($pos, $p, $name);
    }

    /**
     * @param Player $who
     * @throws Exception
     */
    public function onOpen(Player $who)
    {
        $inv = $this;
        $class = new class($inv, $who) extends LuckTask {

            /** @var DoubleMenu $inv */
            private $inv;

            /** @var Player $who */
            private $who;

            /**
             * @param DoubleMenu $inv
             * @param Player $who
             */
            public function __construct(DoubleMenu $inv, Player $who)
            {
                $this->inv = $inv;
                $this->who = $who;
            }

            public function onRun($currentTick)
            {
                $this->inv->finalOpen($this->who);
                parent::onRun($currentTick);
            }
        };
        $class->registerAfter(5);
    }

    /**
     * @param Player $p
     * @throws Exception
     */
    public function finalOpen(Player $p) {
        if(!$p->isCreative()) {
            parent::onOpen($p);
            $this->setItems($this->getItems($p));
        } else {
            throw new Exception(self::IS_CREATIVE);
        }
    }

    /**
     * @param Player $who
     */
    public function onClose(Player $who)
    {
        parent::onClose($who);
        $level = $who->getLevel();
        $blocks = [$level->getBlock($this->chest1), $level->getBlock($this->chest2)];
        $who->level->sendBlocks([$who], $blocks, UpdateBlockPacket::FLAG_ALL);
    }

    public function getHolder(){
        return $this->left->getHolder();
    }

    public function getItem($index){
        return $index < $this->left->getSize() ? $this->left->getItem($index) : $this->right->getItem($index - $this->right->getSize());
    }

    /**
     * @param int $index
     * @param Item $item
     * @param bool $send
     * @return bool
     */
    public function setItem($index, Item $item, $send = true){
        return $index < $this->left->getSize() ? $this->left->setItem($index, $item, $send) : $this->right->setItem($index - $this->right->getSize(), $item, $send);
    }

    /**
     * @param int $index
     * @param bool $send
     * @return bool
     */
    public function clear($index, $send = true){
        return $index < $this->left->getSize() ? $this->left->clear($index, $send) : $this->right->clear($index - $this->right->getSize(), $send);
    }

    public function getContents(){
        $contents = [];
        for($i = 0; $i < $this->getSize(); ++$i){
            $contents[$i] = $this->getItem($i);
        }

        return $contents;
    }

    /**
     * @param Item[] $items
     * @param bool $bool
     */
    public function setContents(array $items, $bool = true){
        if(count($items) > $this->size){
            $items = array_slice($items, 0, $this->size, true);
        }
        for($i = 0; $i < $this->size; ++$i){
            if(!isset($items[$i])){
                if ($i < $this->left->size){
                    if(isset($this->left->slots[$i])){
                        $this->clear($i);
                    }
                }elseif(isset($this->right->slots[$i - $this->left->size])){
                    $this->clear($i, $bool);
                }
            }elseif(!$this->setItem($i, $items[$i], $bool)){
                $this->clear($i, $bool);
            }
        }
    }
}
