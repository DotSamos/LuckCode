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
use function str_replace;

abstract class HelpSubCommandModel extends LuckSubCommand {

    const REPLACE_COMMAND_NAME = '{command_name}';

    const REPLACE_PAGE = '{page}';

    const REPLACE_MAX_PAGES = '{max_pages}';

    const REPLACE_HELP_LIST = '{help_list}';

    const REPLACE_SUBCOMMAND_USAGE = '{sub_command_name}';

    const REPLACE_SUBCOMMAND_DESCRIPTION = '{sub_command_description}';

    const REPLACE_SUBCOMMAND_PREFIX = '{sub_command_prefix}';

    /** @return string */
    protected function getBaseHeader(): string {
        return implode("§r\n", [
            '§8',
            '§6Sub-comandos do §f/' . self::REPLACE_COMMAND_NAME . '§6:',
            '§8',
            self::REPLACE_HELP_LIST,
            '§8',
            '§6Página §f' . self::REPLACE_PAGE . ' §6de §f' . self::REPLACE_MAX_PAGES,
            '§8'
        ]);
    }

    /** @return string */
    protected function getBaseHelpListLine(): string {
        return self::REPLACE_SUBCOMMAND_PREFIX . '§f' . self::REPLACE_SUBCOMMAND_USAGE . ' §6 - §7' . self::REPLACE_SUBCOMMAND_DESCRIPTION;
    }

    /** @return string */
    protected function getSubCommandListPrefix(): string {
        return ' §6> ';
    }

    /** @return int */
    protected function getPageLength(): int {
        return 5;
    }

    /** @return string */
    public function getName(): string {
        return 'help';
    }

    public function getDescription(): string {
        return 'Veja os sub-comandos disponíveis';
    }

    /** @return string */
    public function getUsage(): string {
        return '/' . $this->baseCommand->getName() . ' <página=1>';
    }

    /** @return string[] */
    public function getAliases(): array {
        return ['ajuda', '?'];
    }

    /**
     * @param CommandSender $sender
     * @return bool
     */
    public function canExecute(CommandSender $sender): bool {
        return true;
    }

    /**
     * @param CommandSender $s
     * @param array $args
     */
    public function execute(CommandSender $s, array $args) {
        $page = 1;
        if (isset($args[0])) {
            if (!is_numeric($args[0]) || $args[0] < 1) {
                $s->sendMessage('§cArgumentos inválidos, use ' . $this->getUsage());
                return;
            }
            $page = (int)$args[0];
        }

        $list = $this->getHelpList($s);

        $pageLength = $this->getPageLength();
        $totalPages = (int)ceil(count($list) / $pageLength);

        if ($page > $totalPages) {
            $s->sendMessage('§cA página #' . $page . ' não existe! Use ' . $this->getUsage());
            return;
        }

        $list = $this->getPage($list, $page, $pageLength);

        $searchList = [self::REPLACE_MAX_PAGES, self::REPLACE_PAGE, self::REPLACE_HELP_LIST, self::REPLACE_COMMAND_NAME];
        $replacesList = [$totalPages, $page, implode("\n", $list), $this->baseCommand->getName()];

        $s->sendMessage(str_replace($searchList, $replacesList, $this->getBaseHeader()));
    }

    /**
     * @param CommandSender $s
     * @return array
     */
    private function getHelpList(CommandSender $s): array {
        $subCommands = $this->baseCommand->getSubCommands();
        $subCommands = array_filter($subCommands, function (LuckSubCommand $subCommand) use ($s) {
            return $subCommand->canExecute($s) && $subCommand !== $this;
        });

        $searchList = [self::REPLACE_SUBCOMMAND_DESCRIPTION, self::REPLACE_SUBCOMMAND_PREFIX, self::REPLACE_SUBCOMMAND_USAGE];

        return array_values(array_map(function (LuckSubCommand $subCommand) use ($searchList) {
            $replaceList = [$subCommand->getDescription(), $this->getSubCommandListPrefix(), $subCommand->getUsage()];
            return str_replace($searchList, $replaceList, $this->getBaseHelpListLine());
        }, $subCommands));
    }

    /**
     * @param array $lines
     * @param int $page
     * @param int $totalPerPage
     * @return array
     */
    public function getPage(array $lines, int $page, int $totalPerPage = 5): array {

        # Sim, parte disto foi pego na classe do comando /help da api do Pocketmine-MP

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