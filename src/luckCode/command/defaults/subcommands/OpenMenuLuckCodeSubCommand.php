<?php

declare(strict_types=1);

namespace luckCode\command\defaults\subcommands;

use luckCode\command\LuckSubCommand;
use luckCode\LuckCodePlugin;
use luckCode\menu\types\TestDoubleMenu;
use luckCode\menu\types\TestDoublePaginatedMenu;
use luckCode\menu\types\TestNormalMenu;
use luckCode\menu\types\TestNormalPaginatedMenu;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use Throwable;
use function strtolower;

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
                $name = '§l§5Luck§bCode§r§7 v' . LuckCodePlugin::VERSION;

                if ($type == 'normal') {
                    $inv = $isPaginated ? TestNormalPaginatedMenu::class : TestNormalMenu::class;
                } else if ($type == 'double') {
                    $inv = $isPaginated ? TestDoublePaginatedMenu::class : TestDoubleMenu::class;
                } else {
                    $s->sendMessage($prefix . '§cO tipo de menu/window ' . $type . ' não existe! [normal/double]');
                }
                if (isset($inv)) {
                    try {
                        $inv = new $inv($s, $name);
                        $s->addWindow($inv);
                        $s->sendMessage($prefix . '§aMenu criado.');
                    } catch (Throwable $e) {
                        $s->sendMessage('§cNão foi possivel gerar o menu: §f' . $e->getMessage());
                    }
                }
            }
        }
    }
}