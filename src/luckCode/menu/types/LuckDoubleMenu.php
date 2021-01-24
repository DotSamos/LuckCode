<?php 

declare(strict_types=1);

namespace luckCode\menu\types;

use luckCode\menu\DoubleMenu;
use luckCode\menu\NormalMenu;
use luckCode\scheduler\LuckTask;
use pocketmine\Player;
use pocketmine\item\Item;

class LuckDoubleMenu extends DoubleMenu {

    /** @var LuckTask $updateTask */
    private $updateTask;

	/** @param Player $player */
    public function onOpenMenu(Player $player) {
        $class = new class($this) extends LuckTask {

            private $window;

            private $order = [];

            public function __construct(DoubleMenu $window)
            {
                $this->window = $window;
            }

            public function onRun($currentTick)
            {
                $window = $this->window;
                $newItems = [];
                for ($i = 16; $i < 31; $i++) {
                    $items = Item::getCreativeItems();
                    if (empty($this->order)) {
                        $item = $items[array_rand($items, 1)];
                        $newItems[$i] = $item;
                    } else {
                        foreach ($this->order as $k => $v) {
                            $slot = $k + 1;
                            if ($slot == 32) {
                                continue;
                            } else {
                                $newItems[$slot] = $v;
                            }
                        }
                        $item = $items[array_rand($items, 1)];
                        $newItems[16] = $item;
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