<?php

declare(strict_types=1);

namespace luckCode\command\defaults;

use luckCode\command\defaults\subcommands\luckCode\HelpLuckCodeSubCommand;
use luckCode\command\defaults\subcommands\luckCode\SpawnHolographicSubCommand;
use luckCode\command\LuckCommand;
use luckCode\LuckCodePlugin;

class LuckCodeCommand extends LuckCommand
{
    public function __construct()
    {
        parent::__construct('luckcode', 'LuckCode v'.LuckCodePlugin::VERSION.' by SamosMC', '/lc help', ['lc']);
    }

    /**
     * @inheritDoc
     */
    public function getDefaultSubCommands(): array
    {
        return [SpawnHolographicSubCommand::class, HelpLuckCodeSubCommand::class];
    }
}