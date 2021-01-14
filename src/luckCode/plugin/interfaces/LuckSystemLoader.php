<?php

namespace luckCode\plugin\interfaces;

use luckCode\system\controller\SystemController;

interface LuckSystemLoader
{
    /** @return string[] */
    public function getSystemStatusList() : array;

    /** @return string[] */
    public function getSystemsBases() : array;

    /** @return SystemController */
    public function getSystemController() : SystemController;
}