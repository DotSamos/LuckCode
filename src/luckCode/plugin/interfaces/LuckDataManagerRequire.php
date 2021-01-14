<?php

namespace luckCode\plugin\interfaces;

use luckCode\data\manager\DataManager;

interface LuckDataManagerRequire
{
    /** @return DataManager */
    public function getDataManager(): DataManager;

    /** @return string */
    public function getBaseDataManager(): string;
}