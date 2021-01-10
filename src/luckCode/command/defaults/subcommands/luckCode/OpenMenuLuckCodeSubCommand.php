<?php

declare(strict_types=1);

namespace luckCode\command\defaults\subcommands\luckCode;

use luckCode\command\LuckSubCommand;
use luckCode\LuckCodePlugin;
use luckCode\menu\NormalMenu;
use luckCode\menu\types\TestDoubleMenu;
use luckCode\menu\types\TestNormalMenu;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use Throwable;
use function strtolower;

class OpenMenuLuckCodeSubCommand extends LuckSubCommand
{

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'open';
    }

    /**
     * @inheritDoc
     */
    public function getAliases(): array
    {
        return ['abrir'];
    }

    /**
     * @inheritDoc
     */
    public function getUsage(): string
    {
        return '/lc open [normal/double]';
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return 'Abra um menu/window de testes';
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
    public function execute(CommandSender $s, array $args)
    {
        if($s instanceof Player) {
            $prefix = LuckCodePlugin::PREFIX;
            $type = $args[0] ?? null;
            if($type == null) {
                $s->sendMessage($prefix.'§cArgumentos inválidos! Use '.$this->getUsage());
            } else {
                $type = strtolower($type);
                $name = '§l§5Luck§bCode§r§7 v'.LuckCodePlugin::VERSION;
                if($type == 'normal') {
                    $inv = TestNormalMenu::class;
                } else if($type == 'double') {
                    $inv = TestDoubleMenu::class;
                } else {
                    $s->sendMessage($prefix.'§cO tipo de menu/window '.$type.' não existe!');
                }
                if(isset($inv)) {
                    try {
                        $inv = new $inv($s, $name);
                        $s->addWindow($inv);
                        $s->sendMessage($prefix.'§aMenu/Window aberta.');
                    } catch (Throwable $e) {
                        $s->sendMessage('§c'.$e->getMessage());
                    }
                }
             }
        }
    }
}