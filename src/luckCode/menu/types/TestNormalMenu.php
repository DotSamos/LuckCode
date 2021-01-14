<?php

declare(strict_types=1);

namespace luckCode\menu\types;

use Exception;
use luckCode\menu\NormalMenu;
use luckCode\scheduler\LuckTask;
use pocketmine\item\Item;
use pocketmine\Player;
use function array_rand;
use function count;

class TestNormalMenu extends NormalMenu
{

    /** @var LuckTask $taskUpdate */
    private $taskUpdate;

    /**
     * @param Player $who
     * @throws Exception
     */
    public function onOpen(Player $who)
    {
        parent::onOpen($who);

        $window = $this;
        $class = new class($window) extends LuckTask {

            /** @var NormalMenu $window */
            private $window;

            /** @var Item[] $order */
            private $order = [];

            public function __construct(NormalMenu $window)
            {
                $this->window = $window;
            }

            public function onRun($currentTick)
            {
                $window = $this->window;
                $newItems = [];
                for ($i = 8; $i < 16; $i++) {
                    $items = Item::getCreativeItems();
                    if (empty($this->order)) {
                        $item = $items[array_rand($items, 1)];
                        $newItems[$i] = $item;
                    } else {
                        foreach ($this->order as $k => $v) {
                            $slot = $k + 1;
                            if ($slot == 16) {
                                continue;
                            } else {
                                $newItems[$slot] = $v;
                            }
                        }
                        $item = $items[array_rand($items, 1)];
                        $newItems[8] = $item;
                        break;
                    }
                }
                $window->setItems($newItems);
                $this->order = $newItems;
            }
        };
        $class->registerToRepeat(5);
        $this->taskUpdate = $class;
    }

    public function onClose(Player $who)
    {
        parent::onClose($who);
        if (count($this->getViewers())) {
            $this->taskUpdate->cancel();
        }
    }

    /**
     * @inheritDoc
     */
    public function getItems(Player $p): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function processClick(Player $p, Item $item): bool
    {
        return true;
    }
}