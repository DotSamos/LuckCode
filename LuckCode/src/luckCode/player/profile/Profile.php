<?php

declare(strict_types=1);

namespace luckCode\player\profile;

use pocketmine\Player;
use pocketmine\Server;

abstract class Profile implements interfaces\IProfile
{

    /** @var Player $name */
    protected $name;

    /**
     * Profile constructor.
     * @param string $player
     */
    public function __construct(string $player)
    {
        $this->name = $player;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getPlayer()
    {
        return Server::getInstance()->getPlayer($this->name);
    }
}