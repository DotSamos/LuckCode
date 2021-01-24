<?php 

declare(strict_types=1);

namespace luckCode\menu;

use luckCode\menu\NormalMenu;
use luckCode\menu\holder\BaseMenuHolder;
use luckCode\menu\page\interfaces\IPage;
use luckCode\menu\page\interfaces\IPaginatedMenu;
use pocketmine\Player;
use pocketmine\inventory\InventoryType;
use pocketmine\item\Item;

abstract class PaginatedNormalMenu extends NormalMenu implements IPaginatedMenu {

	/** @var IPage $page */
	private $page;

    /**
     * MenuBase constructor.
     * @param InventoryHolder $holder
     * @param InventoryType $type
     */
    public function __construct(BaseMenuHolder $holder, InventoryType $type) {
        parent::__construct($holder, $type, [], null, null);
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