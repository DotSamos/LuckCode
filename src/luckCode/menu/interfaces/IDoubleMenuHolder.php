<?php

namespace luckCode\menu\interfaces;

use luckCode\menu\utils\DoubleMenuWindow;
use pocketmine\Player;
use pocketmine\level\Position;
use pocketmine\tile\Chest;

interface IDoubleMenuHolder {

    /** @return Chest|null */
    public function getOldTile();

    /**
     * @param Player[]|Player|null $player
     */
    public function spawnTo($player = null);

    /** @return Position */
    public function getPairPosition() : Position;

    public function getPairX() : int;
    public function getPairZ() : int;

    public function getLeft() : DoubleMenuWindow;
    public function getRight() : DoubleMenuWindow; 
}