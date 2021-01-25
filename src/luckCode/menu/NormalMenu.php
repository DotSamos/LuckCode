<?php

declare(strict_types=1);

namespace luckCode\menu;

use luckCode\LuckCodePlugin;
use luckCode\data\types\YamlData;
use pocketmine\Player;

abstract class NormalMenu extends MenuBase {

    /** @return int */
    public function getOpenCooldown() : int {
        return $this->getConfig()->getByRoute('actions_cooldown.normal.open');
    }
    
    /** @return int */
    public function getCloseCooldown() : int {
        return $this->getConfig()->getByRoute('actions_cooldown.normal.close');
    }
}