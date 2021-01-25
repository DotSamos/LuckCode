<?php

declare(strict_types=1);

namespace luckCode\command\defaults\subcommands;

use luckCode\command\LuckSubCommand;
use luckCode\entity\EntityManager;
use luckCode\LuckCodePlugin;
use pocketmine\command\CommandSender;
use pocketmine\entity\Entity;
use pocketmine\Player;

class SpawnHolographicSubCommand extends LuckSubCommand {

    /** @return string */
    public function getName(): string {
        return 'spawnholograpic';
    }

    /** @return string[] */
    public function getAliases(): array {
        return ['spawnh'];
    }

    /** @return string */
    public function getUsage(): string {
        return '/lc spawnh';
    }

    /** @return string */
    public function getDescription(): string {
        return 'Crie um holograma de desmonstração em sua posição.';
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
            $loc = $s->getLocation();
            $compound = EntityManager::getBaseSpawnCompound($loc);
            $entity = Entity::createEntity('LuckHolographicEntity', $s->chunk, $compound);
            $entity->spawnToAll();
            $s->sendMessage(LuckCodePlugin::PREFIX . '§aHolograma de desmonstração criado!');
        }
    }
}