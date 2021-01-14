<?php

declare(strict_types=1);

namespace luckCode\command\models;

use luckCode\command\LuckSubCommand;
use pocketmine\command\CommandSender;
use function array_chunk;
use function array_filter;
use function array_map;
use function array_values;
use function ceil;
use function count;
use function implode;
use function is_numeric;

abstract class HelpSubCommandModel extends LuckSubCommand
{

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'help';
    }

    public function getDescription(): string
    {
        return 'Veja os comandos disponíveis.';
    }

    /**
     * @inheritDoc
     */
    public function getAliases(): array
    {
        return ['ajuda', '?'];
    }

    /**
     * @inheritDoc
     */
    public function canExecute(CommandSender $sender): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function execute(CommandSender $s, array $args)
    {
        $page = 1;
        if (isset($args[0])) {
            if (!is_numeric($args[0]) || $args[0] < 1) {
                $s->sendMessage('§cArgumentos inválidos, use ' . $this->getUsage());
                return;
            }
            $page = (int)$args[0];
        }
        $list = $this->getHelpList($s);
        $totalPerPage = 5;
        $totalPages = ceil(count($list) / $totalPerPage);
        if ($page > $totalPages) {
            $s->sendMessage('§cA página #' . $page . ' não existe! Use ' . $this->getUsage());
            return;
        }
        $header = "§8\n§aAjuda /{$this->baseCommand->getName()} §7[§f" . $page . "§7/§f" . $totalPages . "§7]§r\n§8\n§8";
        $list = $this->getPage($list, $page, $totalPerPage);
        $s->sendMessage($header . implode("\n", $list) . "§r\n§8");
    }

    public function getUsage(): string
    {
        return '/' . $this->baseCommand->getName() . ' <página>';
    }

    /**
     * @param CommandSender $s
     * @return array
     */
    private function getHelpList(CommandSender $s): array
    {
        $subCommands = $this->baseCommand->getSubCommands();
        $subCommands = array_filter($subCommands, function (LuckSubCommand $subCommand) use ($s) {
            return $subCommand->canExecute($s);
        });
        return array_values(array_map(function (LuckSubCommand $subCommand) {
            return '§a - §f' . $subCommand->getUsage() . ' §7' . $subCommand->getDescription();
        }, $subCommands));
    }

    /**
     * @param array $lines
     * @param int $page
     * @param int $totalPerPage
     * @return array
     */
    public function getPage(array $lines, int $page, int $totalPerPage = 5): array
    {
        $commands = array_chunk($lines, $totalPerPage);
        $pageNumber = min(count($commands), $page);
        if ($pageNumber < 1) {
            $pageNumber = 1;
        }
        $finalPage = [];
        if (isset($commands[$pageNumber - 1])) {
            foreach ($commands[$pageNumber - 1] as $line) {
                $finalPage[] = $line;
            }
        }
        return $finalPage;
    }
}