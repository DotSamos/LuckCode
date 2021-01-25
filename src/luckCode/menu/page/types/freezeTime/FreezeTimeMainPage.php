<?php

declare(strict_types=1);

namespace luckCode\menu\page\types\freezeTime;

use luckCode\LuckCodePlugin;
use luckCode\menu\page\Page;
use luckCode\system\types\FreezeTimeSystem;
use pocketmine\block\Block;
use pocketmine\block\Wool;
use pocketmine\item\Dye;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\Player;

class FreezeTimeMainPage extends Page {

    /**
     * @inheritDoc
     */
    public function getItems(Player $player): array {
        $day = Item::get(Block::WOOL, Wool::YELLOW)->setCustomName('§r§eDia');
        $midday = Item::get(Block::WOOL, Wool::ORANGE)->setCustomName('§r§6Meio-dia');
        $night = Item::get(Block::WOOL, Wool::BLACK)->setCustomName('§r§3Noite');
        $unFreeze = Item::get(Item::CLOCK)->setCustomName('§r§aDestravar tempo no mundo');

        $timeWord = FreezeTimeSystem::$worlds[$player->level->getName()] ?? null;

        $addUnFreeze = false;
        $slotTime = null;
        if ($timeWord === Level::TIME_DAY) {
            $addUnFreeze = true;
            $slotTime = 2;
        } else if ($timeWord === 6000) {
            $addUnFreeze = true;
            $slotTime = 3;
        } else if ($timeWord === Level::TIME_NIGHT) {
            $addUnFreeze = true;
            $slotTime = 4;
        }

        $items = [
            10 => $day,
            11 => $midday,
            12 => $night
        ];

        for ($i = 2; $i <= 4; $i++) {
            $items[$i] = Item::get(Item::DYE, ($i == $slotTime ? Dye::LIME : Dye::GRAY))->setCustomName("§8");
        }

        if ($addUnFreeze) {
            $items[23] = $unFreeze;
        }
        return $items;
    }

    /**
     * @inheritDoc
     */
    public function onClick(Player $player, Item $item): bool {
        $prefix = LuckCodePlugin::PREFIX;
        $level = $player->getLevel()->getName();
        $name = $item->getCustomName();

        $time = null;
        if ($name == '§r§eDia') {
            $time = Level::TIME_DAY;
        } else if ($name == '§r§6Meio-dia') {
            $time = 6000;
        } else if ($name == '§r§3Noite') {
            $time = Level::TIME_NIGHT;
        } else if ($name == '§r§aDestravar tempo no mundo') {
            unset(FreezeTimeSystem::$worlds[$level]);
            $player->removeWindow($this->menu);
            $player->sendMessage($prefix . '§aTempo no mundo destravado!');
            return true;
        }
        if ($time !== null) {
            FreezeTimeSystem::$worlds[$level] = $time;
            $this->menu->setPage($this); // atualizar itens
        }
        return true;
    }
}