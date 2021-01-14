<?php

namespace luckCode\player\profile\interfaces;

use pocketmine\Player;

interface IProfile
{
    /** @return string */
    public function getName(): string;

    /** @return  Player|null */
    public function getPlayer();

    /** @return bool */
    public function save(): bool;

    /** @return array */
    public function toData(): array;
}