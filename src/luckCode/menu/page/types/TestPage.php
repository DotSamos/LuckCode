<?php

declare(strict_types=1);

namespace luckCode\menu\page\types;

use luckCode\menu\page\interfaces\IPage;
use luckCode\menu\page\interfaces\IPaginatedMenu;
use luckCode\menu\page\Page;
use luckCode\utils\text\TextFormatter;
use pocketmine\item\Item;
use pocketmine\Player;
use function array_rand;
use function get_class;

abstract class TestPage extends Page {

    /** @var Item $items */
    private $items = [];

    /** @var array $args */
    private $args;

    /**
     * TestMenuPage constructor.
     * @param IPaginatedMenu $menu
     * @param int $startAt
     * @param int $endAt
     * @param int $slotBack
     * @param int $nextSlot
     */
    public function __construct(IPaginatedMenu $menu, int $startAt, int $endAt, int $slotBack, int $nextSlot) {
        $items = [];
        for ($i = $startAt; $i < $endAt; $i++) {
            $items[$i] = Item::getCreativeItems()[array_rand(Item::getCreativeItems(), 1)];
        }
        $items[$slotBack] = Item::get(Item::ARROW)->setCustomName(TextFormatter::center("§r§aVoltar\n§7<"));
        $items[$nextSlot] = Item::get(Item::ARROW)->setCustomName(TextFormatter::center("§r§aPróxima\n§7>"));
        $this->items = $items;
        $this->args = [$startAt, $endAt, $slotBack, $nextSlot];
        parent::__construct($menu);
    }

    /**
     * @inheritDoc
     */
    public function getItems(Player $player): array {
        return $this->items;
    }

    /**
     * @inheritDoc
     */
    public function onClick(Player $player, Item $item): bool {
        $name = $item->getCustomName();
        if ($name == TextFormatter::center("§r§aVoltar\n§7<")) {
            $this->menu->redoPage();
        } else if ($name == TextFormatter::center("§r§aPróxima\n§7>")) {
            $class = get_class($this);
            /** @var IPage $page */
            $page = new $class($this->menu, ...$this->args);
            $page->setRedoPage($this);
            $this->menu->setPage($page);
        }
        return true;
    }
}