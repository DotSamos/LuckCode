<?php 

declare(strict_types=1);

namespace luckCode\menu\types;

use luckCode\menu\NormalMenu;
use luckCode\scheduler\LuckTask;
use pocketmine\Player;
use pocketmine\item\Item;

class LuckNormalMenu extends NormalMenu {

    /** @var LuckTask $updateTask */
    private $updateTask;

	/** @param Player $player */
    public function onOpenMenu(Player $player) {
        $class = new class($this) extends LuckTask {

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
        $this->updateTask = $class;
    }

    /** @param Player $player */
    public function onCloseMenu(Player $player) {
        $this->updateTask->cancel();
    }

     /**
     * @param Player $p
     * @param Item $item
     * @return bool
     */
    public function processClick(Player $p, Item $item): bool {
    	return true;
    }
}
