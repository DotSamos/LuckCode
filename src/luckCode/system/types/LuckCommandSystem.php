<?php

declare(strict_types=1);

namespace luckCode\system\types;

use luckCode\command\defaults\LuckCodeCommand;
use luckCode\LuckCodePlugin;
use luckCode\system\System;

class LuckCommandSystem extends System
{

    const NAME = 'LuckCommand';

    /** @var LuckCodeCommand $command */
    private $command;

    public function onEnable()
    {
        $cmd = new LuckCodeCommand();
        $this->command = $cmd;
        $cmd->registerCommand(LuckCodePlugin::getInstance(), 'samos.luckcode.command');
    }

    public function onDisable()
    {
        $this->command->unregister(LuckCodePlugin::getInstance()->getServer()->getCommandMap());
    }
}