<?php 

declare(strict_types=1);

namespace luckCode\menu;

use luckCode\menu\DoubleMenu;
use luckCode\menu\holder\BaseMenuHolder;
use luckCode\menu\page\interfaces\IPage;
use luckCode\menu\page\interfaces\IPaginatedMenu;
use pocketmine\Player;
use pocketmine\item\Item;

abstract class PaginatedDoubleMenu extends DoubleMenu implements IPaginatedMenu {

	/** @var IPage $page */
	private $page;

    /**
     * PaginatedDoubleMenu constructor.
     * @param InventoryHolder $holder
     */
    public function __construct(BaseMenuHolder $holder) {
        parent::__construct($holder);
        $this->getMainPage();
    }

	/** @param IPage $page */
    public function setPage(IPage $page) {
        $this->page = $page;
        $holder = $this->getHolder();
        if($holder instanceof BaseMenuHolder && $holder->getHandler()) {
            $items = $page->getItems($holder->getHandler());
            $this->setItems($items);
        }
    }

    /** @return IPage|null */
    public function redoPage() {
        $page = $this->page;
        if($page && ($redo = $page->getRedoPage())) {
            $this->setPage($redo);   
        }
    }

    /**
     * @param Player $p
     * @param Item $item
     * @return bool
     */
    public function processClick(Player $p, Item $item): bool {
        if($this->page) {
            return $this->page->onClick($p, $item);
        }
    }
}