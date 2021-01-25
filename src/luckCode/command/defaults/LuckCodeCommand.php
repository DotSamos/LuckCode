<?php

declare(strict_types=1);

namespace luckCode\command\defaults;

use luckCode\command\defaults\subcommands\FastKillSubCommand;
use luckCode\command\defaults\subcommands\FormatTextLuckCodeSubCommand;
use luckCode\command\defaults\subcommands\FreezeTimeLuckSubCommand;
use luckCode\command\defaults\subcommands\HelpLuckCodeSubCommand;
use luckCode\command\defaults\subcommands\OpenMenuLuckCodeSubCommand;
use luckCode\command\defaults\subcommands\SpawnHolographicSubCommand;
use luckCode\command\LuckCommand;
use luckCode\LuckCodePlugin;

class LuckCodeCommand extends LuckCommand {

    public function __construct() {
        parent::__construct('luckcode', 'LuckCode v' . LuckCodePlugin::VERSION . ' by SamosMC and SmallkingDev_', '/lc help', ['lc']);
    }

    /** @return string[] */
    public function getDefaultSubCommands(): array {
        return [
            SpawnHolographicSubCommand::class,
            HelpLuckCodeSubCommand::class,
            OpenMenuLuckCodeSubCommand::class,
            FormatTextLuckCodeSubCommand::class,
            FastKillSubCommand::class,
            FreezeTimeLuckSubCommand::class
        ];
    }
}