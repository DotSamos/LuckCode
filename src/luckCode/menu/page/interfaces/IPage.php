<?php

namespace luckCode\menu\page\interfaces;

use pocketmine\item\Item;
use pocketmine\Player;

interface IPage
{

    /**
     * @param Player $player
     * @return Item[]
     */
    public function getItems(Player $player): array;

    /** @param Player $player */
    public function sendItems(Player $player);

    /** @param IPage $page */
    public function setRedoPage(IPage $page);

    /** @return IPage|null */
    public function getRedoPage();

    /**
     * @param Player $player
     * @param Item $item
     * @return bool
     */
    public function onClick(Player $player, Item $item): bool;
}