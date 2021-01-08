<?php

declare(strict_types=1);

namespace luckCode\command;

use pocketmine\command\CommandSender;

abstract class LuckSubCommand
{

    /** @return string */
    public abstract function getName() : string;

    /** @return string[] */
    public abstract function getAliases() : array;

    /** @return string */
    public abstract function getUsage() : string;

    /**
     * @param CommandSender $sender
     * @return bool
     */
    public abstract function canExecute(CommandSender $sender) : bool;

    /**
     * @param CommandSender $sender
     * @param array $args
     * @return mixed
     */
    public abstract function execute(CommandSender $sender, array $args);
}