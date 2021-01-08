<?php

declare(strict_types=1);

namespace luckCode\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use function array_filter;
use function array_map;
use function array_values;

abstract class LuckCommand extends Command
{

    /** @var LuckSubCommand[] $subCommands */
    private $subCommands = [];

    public function __construct($name, $description = "", $usageMessage = null, array $aliases = [])
    {
        $this->loadSubCommands();
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    private function loadSubCommands()
    {
        $this->subCommands = array_map(function (string $subcmd) {
            return new $subcmd();
        }, $this->getSubCommands());
    }

    /** @return string[] */
    public abstract function getSubCommands(): array;

    /**
     * @param CommandSender $s
     * @param string $commandLabel
     * @param array $args
     * @return bool
     */
    public function execute(CommandSender $s, $commandLabel, array $args): bool
    {
        $helpUsage = '§cUse §7/' . $this->getName() . ' help §cpara ver a lista de comandos disponíveis!';
        if (empty($args[0])) {
            $s->sendMessage($helpUsage);
        } else {
            /** @var LuckSubCommand|null $found */
            $found = array_values(
                    array_filter($this->subCommands, function (LuckSubCommand $subCommand) use ($s) {
                        return $subCommand->canExecute($s);
                    })
                )[0] ?? null;
            if($found) {
                unset($args[0]);
                $found->execute($s, array_values($args));
                return true;
            } else {
                $s->sendMessage($helpUsage);
            }
        }
        return false;
    }
}