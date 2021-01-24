<?php 

namespace luckCode\menu\types;

use luckCode\menu\PaginatedDoubleMenu;
use luckCode\menu\PaginatedNormalMenu;
use luckCode\menu\page\interfaces\IPage;
use luckCode\menu\page\types\DoubleTestPage;
use pocketmine\Player;

class LuckPaginatedDoubleMenu extends PaginatedDoubleMenu {


	/** @param Player $player */
    public function onOpenMenu(Player $player) {

    }

    /** @param Player $player */
    public function onCloseMenu(Player $player) {

    }

	/** @return IPage */
    public function getMainPage(): IPage {
    	return new DoubleTestPage($this);
    }    
}