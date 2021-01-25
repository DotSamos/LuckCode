<?php

declare(strict_types=1);

namespace luckCode\menu\page\types;

use luckCode\menu\page\interfaces\IPaginatedMenu;

class DoubleTestPage extends TestPage {

    /**
     * DoubleTestPage constructor.
     * @param IPaginatedMenu $menu
     */
    public function __construct(IPaginatedMenu $menu) {
        parent::__construct($menu, 8, 40, 40, 47);
    }

}