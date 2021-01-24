<?php

declare(strict_types=1);

namespace luckCode\menu;

use function count;
use luckCode\LuckCodePlugin;
use luckCode\data\types\YamlData;
use luckCode\menu\DoubleMenu;
use luckCode\menu\controller\MenuController;
use luckCode\menu\holder\BaseMenuHolder;
use luckCode\menu\interfaces\IMenu;
use luckCode\utils\SimpleCooldown;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\HandlerList;
use pocketmine\event\Listener;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\inventory\CustomInventory;
use pocketmine\inventory\InventoryHolder;
use pocketmine\inventory\InventoryType;
use pocketmine\item\Item;

abstract class MenuBase extends CustomInventory implements IMenu, Listener {

    /**
     * MenuBase constructor.
     * @param InventoryHolder $holder
     * @param InventoryType $type
     */
    public function __construct(BaseMenuHolder $holder, InventoryType $type) {
        parent::__construct($holder, $type, [], null, null);
        MenuController::put($this);
        Server::getInstance()->getPluginManager()->registerEvents($this, LuckCodePlugin::getInstance());
        if ($holder instanceof BaseMenuHolder && $holder->getHandler()) {
            $this->onOpenMenu($holder->getHandler());
        }
    }

    /** @return int */
    public abstract function getOpenCooldown() : int;
    
    /** @return int */
    public abstract function getCloseCooldown() : int;

    /** @return YamlData */
    protected function getConfig() : YamlData {
        return LuckCodePlugin::getInstance()->getDataManager()->get('menu');
    }

    /** @param Item[] $items */
    public function setItems(array $items) {
        $this->clearAll(false);
        foreach($items as $slot => $item) {
            $this->setItem($slot, $item, false);
        }
        $this->sendContents($this->getViewers());
    }

    /** @param Player $who */
    public function onOpen(Player $who) {
        $holder = $this->getHolder();
        if($holder instanceof BaseMenuHolder) {
            $holder->spawnTo($who);
        }
        new class ($this, $who) extends SimpleCooldown {

            /** @var MenuBase $menu */
            private $menu;

            /** @var Player $player */
            private $player;

            /**
             * @param MenuBase $menu
             * @param Player $player
             */
            public function __construct(MenuBase $menu, Player $player) {
                $this->menu = $menu;
                $this->player = $player;
                parent::__construct($menu->getOpenCooldown());
            }

            public function execute() {
                $this->menu->finalOpen($this->player);
            }
        };
    }

    /** @param Player $p */
    public function finalOpen(Player $p) {
        parent::onOpen($p);
    }

    /** @param Player $who */
    public function onClose(Player $who) {
        new class ($this, $who) extends SimpleCooldown {

            /** @var MenuBase $menu */
            private $menu;

            /** @var Player $player */
            private $player;

            /**
             * @param MenuBase $menu
             * @param Player $player
             */
            public function __construct(MenuBase $menu, Player $player) {
                $this->menu = $menu;
                $this->player = $player;
                parent::__construct($menu->getCloseCooldown());
            }

            public function execute() {
                $this->menu->finalClose($this->player);
            }
        };

        $holder = $this->getHolder();
        if ($holder instanceof BaseMenuHolder) {
            $holder->removeTo($who);
        }
        $this->fixFloatingInventory($who);
    }

    /** @param Player $p */
    public function finalClose(Player $p) {
        parent::onClose($p);
        if (count($this->getViewers()) < 1) {
            $this->onCloseMenu($p);
            HandlerList::unregisterAll($this);
            MenuController::unset($this);
        }
    }

    /**
     * @param InventoryTransactionEvent $e
     * @priority HIGHEST
     */
    public function onTransactionEvent(InventoryTransactionEvent $e) {
        $transaction = $e->getTransaction();
        $handler = $transaction->getPlayer();
        foreach ($transaction->getTransactions() as $action) {
            $addItem = $action->getChange()['in'];
            $removedItem = $action->getChange()['out'];

            $inventory = $action->getInventory();
            if(!$inventory) continue;
            $holder = $inventory->getHolder();
            if (
                (
                 $inventory === $this &&
                 $holder instanceof BaseMenuHolder &&
                 $handler instanceof Player 
                ) &&
                (
                 (
                  $removedItem &&
                  $this->processClick($handler, $removedItem)
                  ) ||
                  (
                    $addItem && 
                    !$holder->canAddItems()
                  )
                )
            ) {
                $e->setCancelled(true);
                $this->fixFloatingInventory($handler);
            }
        }
    }

    /** @param Player $p */
    private function fixFloatingInventory(Player $p) {
        $dataManager = LuckCodePlugin::getInstance()->getDataManager();
        if((bool)$dataManager->get('menu')->get('fix_floating_inventory', true)) {
            $floatingInv = $p->getFloatingInventory();
            $floatingContents = $floatingInv->getContents();
            if(count($floatingContents) > 0) {
                $pInv = $p->getInventory(); 
                $pInv->addItem(...$floatingContents);
                $pInv->sendContents($p);
                $floatingInv->clearAll(false);
            }
        }
    }
}