<?php

declare(strict_types=1);

namespace luckCode\command\defaults\subcommands\luckCode;

use luckCode\command\LuckSubCommand;
use luckCode\entity\EntityManager;
use luckCode\LuckCodePlugin;
use pocketmine\command\CommandSender;
use pocketmine\entity\Entity;
use pocketmine\Player;

class SpawnHolographicSubCommand extends LuckSubCommand
{

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'spawnholograpic';
    }

    /**
     * @inheritDoc
     */
    public function getAliases(): array
    {
        return ['spawnh'];
    }

    /**
     * @inheritDoc
     */
    public function getUsage(): string
    {
        return '/lc spawnh';
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return 'Crie um holograma de teste na sua posição.';
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
        if ($s instanceof Player) {
            $loc = $s->getLocation();
            $compound = EntityManager::getBaseSpawnCompound($loc);
            $entity = Entity::createEntity('LuckHolographicEntity', $s->chunk, $compound);
            $entity->spawnToAll();
            $s->sendMessage(LuckCodePlugin::PREFIX . '§aHolograma de teste criado!');
        }
    }
}