<?php

declare(strict_types=1);

namespace luckCode\menu;

use luckCode\menu\page\interfaces\IPage;
use luckCode\menu\page\interfaces\IPaginatedMenu;
use pocketmine\item\Item;
use pocketmine\Player;
use function array_search;
use function array_values;
use function count;
use function is_numeric;
use function range;

abstract class DoublePaginatedMenu extends DoubleMenu implements IPaginatedMenu
{

    /** @var IPage $page */
    protected $page;

    /**
     * @inheritDoc
     */
    public function getItems(Player $p): array
    {
        return $this->getMainPage()->getItems($p);
    }

    /**
     * @inheritDoc
     */
    public function setPage(IPage $page)
    {
        $this->page = $page;
        $this->setItems($page->getItems(array_values($this->viewers)[0]));
    }

    public function redoPage()
    {
        $redo = $this->page->getRedoPage();
        if($redo) $this->setPage($redo);
    }
    /**
     * @inheritDoc
     */
    public function processClick(Player $p, Item $item): bool
    {
        return $this->page->onClick($p, $item);
    }
}