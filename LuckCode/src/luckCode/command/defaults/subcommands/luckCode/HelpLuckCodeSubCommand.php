<?php

declare(strict_types=1);

namespace luckCode\command\defaults\subcommands\luckCode;

use luckCode\command\models\HelpSubCommandModel;

class HelpLuckCodeSubCommand extends HelpSubCommandModel
{
    public function getUsage(): string
    {
        return '/lc help <página>';
    }
}