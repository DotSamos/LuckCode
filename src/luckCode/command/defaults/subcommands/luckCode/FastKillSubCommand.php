<?php

namespace luckCode\command\defaults\subcommands\luckCode;

use luckCode\command\LuckSubCommand;
use luckCode\LuckCodePlugin;
use luckCode\utils\EntityController;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class FastKillSubCommand extends LuckSubCommand
{

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'fastkill';
    }

    /**
     * @inheritDoc
     */
    public function getAliases(): array
    {
        return ['fk'];
    }

    /**
     * @inheritDoc
     */
    public function getUsage(): string
    {
        return '/lc fk';
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return 'Ative\desative o modo fastkill';
    }

    /**
     * @inheritDoc
     */
    public function canExecute(CommandSender $sender): bool
    {
        return $sender instanceof Player && $sender->hasPermission(LuckCodePlugin::ADMIN_PERMISSION);
    }

    /**
     * @inheritDoc
     */
    public function execute(CommandSender $sender, array $args)
    {
        $sender->sendMessage(LuckCodePlugin::PREFIX . (!(EntityController::inFastKill($sender)) ? '§r§aModo fastkill ativado.' : '§r§cModo fastkill desativado.'));
        (!EntityController::inFastKill($sender)) ? EntityController::addFastKill($sender) : EntityController::removeFastKill($sender);
    }
}