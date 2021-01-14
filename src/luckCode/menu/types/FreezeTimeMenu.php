<?php

declare(strict_types=1);

namespace luckCode\menu\types;

use luckCode\menu\NormalPaginatedMenu;
use luckCode\menu\page\interfaces\IPage;
use luckCode\menu\page\types\freezeTime\FreezeTimeMainPage;

class FreezeTimeMenu extends NormalPaginatedMenu
{
    /**
     * @inheritDoc
     */
    public function getMainPage(): IPage
    {
        return new FreezeTimeMainPage($this);
    }
}