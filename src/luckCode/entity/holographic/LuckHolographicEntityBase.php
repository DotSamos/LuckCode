<?php

declare(strict_types=1);

namespace luckCode\entity\holographic;

use luckCode\utils\EntityController;
use pocketmine\entity\Entity;
use pocketmine\entity\Human;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Item;
use pocketmine\network\protocol\AddPlayerPacket;
use pocketmine\Player;
use function spl_object_hash;
use function uniqid;

abstract class LuckHolographicEntityBase extends Human
{

    /** @var string[] $textCache */
    private $textCache = [];

    /**
     * @param int $tick
     * @return bool
     */
    public function onUpdate($tick)
    {
        EntityController::onUpdate($this);
        return parent::onUpdate($tick);
    }

    /**
     * @param float $damage
     * @param EntityDamageEvent $source
     * @return bool|void
     */
    public function attack($damage, EntityDamageEvent $source)
    {
        if (!EntityController::onAttack($source)) return true;
    }

    /**
     * @param Player $p
     * @param string $text
     * @return bool
     */
    public function setNameTagFor(Player $p, string $text): bool
    {
        $hash = spl_object_hash($p);

        if (isset($this->textCache[$hash]) && $this->textCache[$hash] == $text) {
            return false;
        }

        $this->textCache[$hash] = $text;
        $this->sendData($p, [
            self::DATA_NAMETAG => [self::DATA_TYPE_STRING, $text]
        ]);

        return true;
    }

    /**
     * @param Player $player
     */
    public function spawnTo(Player $player)
    {
        if (!(isset($this->hasSpawned[$player->getLoaderId()]))) {

            $this->hasSpawned[$player->getLoaderId()] = $player;

            $uuid = $this->getUniqueId();
            $entityId = $this->getId();

            $pk = new AddPlayerPacket();
            $pk->uuid = $uuid;
            $pk->username = uniqid('');
            $pk->eid = $entityId;
            $pk->x = $this->x;
            $pk->y = $this->y;
            $pk->z = $this->z;
            $pk->yaw = 0;
            $pk->pitch = 0;
            $pk->item = Item::get(Item::AIR);
            $pk->metadata = [
                2 => [4, $this->getName()],
                Entity::DATA_FLAGS => [Entity::DATA_TYPE_BYTE, 1 << Entity::DATA_FLAG_INVISIBLE],
                3 => [0, $this->getDataProperty(3)],
                15 => [0, 1],
                23 => [7, -1],
                24 => [0, 0]
            ];
            $player->dataPacket($pk);

            $this->server->removePlayerListData($this->getUniqueId(), [$player]);
        }
    }
}