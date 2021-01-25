<?php

declare(strict_types=1);

namespace luckCode\menu\utils;

use pocketmine\inventory\ContainerInventory;
use pocketmine\inventory\InventoryHolder;
use pocketmine\inventory\InventoryType;

class DoubleMenuWindow extends ContainerInventory {

    /**
     * DoubleMenuWindow constructor.
     * @param InventoryHolder $holder
     */
    public function __construct(InventoryHolder $holder) {
        parent::__construct($holder, InventoryType::get(InventoryType::CHEST), [], null, null);
    }
}