<?php

declare(strict_types=1);

namespace luckCode\menu\types;

use luckCode\menu\DoublePaginatedMenu;
use luckCode\menu\page\interfaces\IPage;
use luckCode\menu\page\types\DoubleTestPage;

class TestDoublePaginatedMenu extends DoublePaginatedMenu {

    /**
     * @inheritDoc
     */
    public function getMainPage(): IPage {
        return new DoubleTestPage($this);
    }
}