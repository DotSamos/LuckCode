<?php

namespace luckCode\menu\page\interfaces;

use pocketmine\item\Item;

interface IPaginatedMenu {

    /** @param IPage $page */
    public function setPage(IPage $page);

    /** @param Item[] $items */
    public function setItems(array $items);

    /** @return IPage */
    public function getMainPage(): IPage;

    public function redoPage();
}