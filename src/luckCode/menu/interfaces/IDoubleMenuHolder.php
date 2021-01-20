<?php

namespace luckCode\menu\interfaces;

use pocketmine\Player;
use pocketmine\tile\Chest;

interface IDoubleMenuHolder {

    /** @return Chest|null */
    public function getOldTile();

    /**
     * @param Player[]|Player|null $player
     */
    public function spawnTo($player = null);

    public function getPairX() : int;
    public function getPairZ() : int;
}