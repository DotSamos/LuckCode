<?php

declare(strict_types=1);

namespace luckCode\command\defaults\subcommands;

use luckCode\command\LuckSubCommand;
use luckCode\LuckCodePlugin;
use luckCode\menu\types\FreezeTimeMenu;
use luckCode\system\types\FreezeTimeSystem;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use Throwable;

class FreezeTimeLuckSubCommand extends LuckSubCommand {

    /** @return string */
    public function getName(): string {
        return 'freezeTime';
    }

    /** @return string */
    public function getAliases(): array {
        return ['ft', 'time', 'freeze'];
    }

    /** @return string */
    public function getUsage(): string {
        return '/lc ft';
    }

    /** @return string */
    public function getDescription(): string {
        return 'Congele o tempo no seu mundo atual';
    }

    /**
     * @param CommandSender $sender
     * @return bool
     */
    public function canExecute(CommandSender $sender): bool {
        return $sender instanceof Player &&
            LuckCodePlugin::getInstance()->getSystemController()->getSystem(FreezeTimeSystem::NAME);
    }

    /**
     * @param CommandSender $s
     * @param array $args
     */
    public function execute(CommandSender $s, array $args) {
        if ($s instanceof Player) {
            try {
                $menu = new FreezeTimeMenu($s, '§r§bFreeze§l§3Time');
                $s->addWindow($menu);
            } catch (Throwable $e) {
                $s->sendMessage($e->getMessage());
            }
        }
    }
}