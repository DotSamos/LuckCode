<?php

declare(strict_types=1);

namespace luckCode\scheduler;

use luckCode\LuckCodePlugin;
use luckCode\system\types\FreezeTimeSystem;
use luckCode\system\types\LuckCommandSystem;
use luckCode\utils\EntityController;
use luckCode\utils\SimpleCooldown;
use pocketmine\Player;
use pocketmine\Server;
use function array_walk;

class LuckUtilityTask extends LuckTask {

    public function onRun($currentTick) {
        $pl = LuckCodePlugin::getInstance();
        $syController = $pl->getSystemController();

        if ($freezeTime = $syController->getSystem(FreezeTimeSystem::NAME)) {
            foreach ($freezeTime::$worlds as $name => $time) {
                $level = Server::getInstance()->getLevelByName($name);
                if($level) $level->setTime($time);
            }
        }

        if ($syController->getSystem(LuckCommandSystem::NAME)) {
            $all = EntityController::getAllInFastKill();
            array_walk($all, function (Player $p) {
                $p->sendPopup('§r§eModo fast-kill ativo!');
            });
        }

        SimpleCooldown::update();
    }

}