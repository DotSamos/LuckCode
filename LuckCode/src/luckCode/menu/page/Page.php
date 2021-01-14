<?php

declare(strict_types=1);

namespace luckCode\menu\page;

use luckCode\menu\page\interfaces\IPage;
use luckCode\menu\page\interfaces\IPaginatedMenu;
use pocketmine\Player;

abstract class Page implements interfaces\IPage
{

    /** @var IPaginatedMenu $menu */
    protected $menu;

    /** @var IPage $redoPage */
    protected $redoPage;

    /**
     * Page constructor.
     * @param IPaginatedMenu $menu
     */
    public function __construct(IPaginatedMenu $menu)
    {
        $this->menu = $menu;
        $menu->setPage($this);
    }

    /** @inheritDoc */
    public function sendItems(Player $player)
    {
        $this->menu->setItems($this->getItems($player));
    }

    /** @inheritDoc */
    public function setRedoPage(IPage $page)
    {
        $this->redoPage = $page;
    }

    /** @return IPage|null */
    public function getRedoPage()
    {
        return $this->redoPage;
    }
}