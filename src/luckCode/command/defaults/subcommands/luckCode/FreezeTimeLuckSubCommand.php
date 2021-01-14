<?php

declare(strict_types=1);

namespace luckCode\command\defaults\subcommands\luckCode;

use luckCode\command\LuckSubCommand;
use luckCode\LuckCodePlugin;
use luckCode\menu\types\FreezeTimeMenu;
use luckCode\system\types\FreezeTimeSystem;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use Throwable;

class FreezeTimeLuckSubCommand extends LuckSubCommand
{

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'freezeTime';
    }

    /**
     * @inheritDoc
     */
    public function getAliases(): array
    {
        return ['ft'];
    }

    /**
     * @inheritDoc
     */
    public function getUsage(): string
    {
        return '/lc ft';
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return 'Congele o tempo no seu mundo atual';
    }

    /**
     * @inheritDoc
     */
    public function canExecute(CommandSender $sender): bool
    {
        return $sender instanceof Player && LuckCodePlugin::getInstance()->getSystemController()->getSystem(FreezeTimeSystem::NAME) != null;
    }

    /**
     * @inheritDoc
     */
    public function execute(CommandSender $s, array $args)
    {
        if($s instanceof Player) {
            try {
                $menu = new FreezeTimeMenu($s, '§r§bFreeze§l§3Time');
                $s->addWindow($menu);
            } catch (Throwable $e) {
                $s->sendMessage($e->getMessage());
            }
        }
    }
}