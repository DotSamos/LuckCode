<?php

namespace luckCode\command\defaults\subcommands;

use luckCode\command\LuckSubCommand;
use luckCode\LuckCodePlugin;
use luckCode\utils\EntityController;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class FastKillSubCommand extends LuckSubCommand {

    /** @return string */
    public function getName(): string {
        return 'fastkill';
    }

    /** @return string[] */
    public function getAliases(): array {
        return ['fk'];
    }

    /** @return string */
    public function getUsage(): string {
        return '/lc fk';
    }

    /** @return string */
    public function getDescription(): string {
        return 'Entre/saia do modo fastkill';
    }

    /**
     * @param CommandSender $sender
     * @return bool
     */
    public function canExecute(CommandSender $sender): bool {
        return $sender instanceof Player && $sender->hasPermission(LuckCodePlugin::ADMIN_PERMISSION);
    }

    /**
     * @param CommandSender $sender
     * @param array $args
     */
    public function execute(CommandSender $sender, array $args) {
        if($sender instanceof Player) {
            if(EntityController::inFastKill($sender)) {
                EntityController::removeFastKill($sender);
                $message = '§cModo fast-kill desativado!';
            } else {
                EntityController::addFastKill($sender);
                $message = '§aModo fast-kill ativado!';
            }
            $sender->sendMessage(LuckCodePlugin::PREFIX . $message);
        }
    }
}