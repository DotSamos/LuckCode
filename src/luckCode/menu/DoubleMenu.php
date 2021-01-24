<?php

declare(strict_types=1);

namespace luckCode\menu;

use luckCode\LuckCodePlugin;
use luckCode\data\types\YamlData;
use luckCode\menu\holder\BaseMenuHolder;
use luckCode\menu\utils\DoubleMenuWindow;
use pocketmine\inventory\InventoryHolder;
use pocketmine\inventory\InventoryType;
use pocketmine\item\Item;

abstract class DoubleMenu extends MenuBase {

    /** @var DoubleMenuWindow $left */
    protected $left;

    /** @var DoubleMenuWindow $right */
    protected $right;

    /**
     * DoubleMenu constructor.
     * @param InventoryHolder $holder
     */
    public function __construct(BaseMenuHolder $holder) {
        $this->left = $holder->getLeft();
        $this->right = $holder->getRight();
        parent::__construct($holder, InventoryType::get(InventoryType::DOUBLE_CHEST));
    }

    /** @return int */
    public function getOpenCooldown() : int {
        return $this->getConfig()->getByRoute('actions_cooldown.double.open');
    }
    
    /** @return int */
    public function getCloseCooldown() : int {
        return $this->getConfig()->getByRoute('actions_cooldown.double.close');
    }

    /**
     * @param int $index
     * @return Item
     */
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

    /** @return Item[] */
    public function getContents(){
        $contents = [];
        for($i = 0; $i < $this->getSize(); ++$i){
            $contents[$i] = $this->getItem($i);
        }

        return $contents;
    }

    /**
     * @param Item[] $items
     * @param bool $send
     */
    public function setContents(array $items, $send = true){
        if(count($items) > $this->size){
            $items = array_slice($items, 0, $this->size, true);
        }


        for($i = 0; $i < $this->size; ++$i){
            if(!isset($items[$i])){
                if ($i < $this->left->size){
                    if(isset($this->left->slots[$i])){
                        $this->clear($i, $send);
                    }
                }elseif(isset($this->right->slots[$i - $this->left->size])){
                    $this->clear($i, $send);
                }
            }elseif(!$this->setItem($i, $items[$i], $send)){
                $this->clear($i, $send);
            }
        }
    }
}