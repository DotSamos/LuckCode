<?php

namespace luckCode\menu\interfaces;

use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\item\Item;
use pocketmine\Player;

interface IMenu {

    /** @param Player $player */
    public function onOpenMenu(Player $player);

    /** @param Player $player */
    public function onCloseMenu(Player $player);

    /** @param InventoryTransactionEvent $e */
    public function onTransactionEvent(InventoryTransactionEvent $e);

    /**
     * @param Player $p
     * @param Item $item
     * @return bool
     */
    public function processClick(Player $p, Item $item): bool;
}