<?php

declare(strict_types=1);

namespace luckCode\command\defaults\subcommands;

use Throwable;
use function strtolower;
use luckCode\LuckCodePlugin;
use luckCode\command\LuckSubCommand;
use luckCode\menu\NormalMenu;
use luckCode\menu\holder\DoubleMenuHolder;
use luckCode\menu\holder\NormalMenuHolder;
use luckCode\menu\types\LuckDoubleMenu;
use luckCode\menu\types\LuckNormalMenu;
use luckCode\menu\types\LuckPaginatedDoubleMenu;
use luckCode\menu\types\LuckPaginatedNormalMenu;
use pocketmine\Player;
use pocketmine\command\CommandSender;

class OpenMenuLuckCodeSubCommand extends LuckSubCommand {

    /** @return string */
    public function getName(): string {
        return 'open';
    }

    /** @return string[] */
    public function getAliases(): array {
        return ['abrir'];
    }

    /** @return string */
    public function getUsage(): string {
        return '/lc open [normal/double] <-p>';
    }

    /** @return string */
    public function getDescription(): string {
        return 'Abra um menu/window de testes';
    }

    /**
     * @param CommandSender $sender
     * @return bool
     */
    public function canExecute(CommandSender $sender): bool {
        return $sender instanceof Player && $sender->hasPermission(LuckCodePlugin::ADMIN_PERMISSION);
    }

    /**
     * @param CommandSender $s
     * @param array $args
     */
    public function execute(CommandSender $s, array $args) {
        if ($s instanceof Player) {
            $prefix = LuckCodePlugin::PREFIX;
            $invalidArgs = $prefix.'§cArgumentos inválidos! Use ' . $this->getUsage();

            $type = $args[0] ?? null;
            if ($type == null) {
                $s->sendMessage($invalidArgs);
            } else {
                $isPaginated = $args[1] ?? null;

                if ($isPaginated == '-p') {
                    $isPaginated = true;
                } else if($isPaginated != null) {
                    $s->sendMessage($invalidArgs);
                    return;
                }

                $type = strtolower($type);
                $name = '§l§5Luck§r§bCode§7 v' . LuckCodePlugin::VERSION;

                $holderArgs = [$s, null, $name];
                if ($type == 'normal') {
                    $holder = NormalMenuHolder::class;

                    $holderArgs[1] = $isPaginated ? 
                    LuckPaginatedNormalMenu::class : 
                    LuckNormalMenu::class;

                } else if ($type == 'double') {
                    $holder = DoubleMenuHolder::class;

                    $holderArgs[1] = $isPaginated ? 
                    LuckPaginatedDoubleMenu::class : 
                    LuckDoubleMenu::class;

                } else {
                    $s->sendMessage($prefix . '§cO tipo de menu/window ' . $type . ' não existe! [normal/double] <-p>');
                }
                if(isset($holder)) {
                    $holder = new $holder(...$holderArgs);
                    $s->addWindow($holder->getInventory());
                    $s->sendMessage($prefix.'§aMenu/Window aberta!');
                }
            }
        }
    }
}