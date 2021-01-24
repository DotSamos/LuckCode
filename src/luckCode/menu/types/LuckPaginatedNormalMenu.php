<?php 

namespace luckCode\menu\types;

use luckCode\menu\PaginatedNormalMenu;
use luckCode\menu\page\interfaces\IPage;
use luckCode\menu\page\types\NormalTestPage;
use pocketmine\Player;

class LuckPaginatedNormalMenu extends PaginatedNormalMenu {


	/** @param Player $player */
    public function onOpenMenu(Player $player) {

    }

    /** @param Player $player */
    public function onCloseMenu(Player $player) {

    }

	/** @return IPage */
    public function getMainPage(): IPage {
    	return new NormalTestPage($this);
    }    
}