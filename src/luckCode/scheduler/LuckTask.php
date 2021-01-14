<?php

declare(strict_types=1);

namespace luckCode\scheduler;

use pocketmine\scheduler\Task;
use pocketmine\Server;

abstract class LuckTask extends Task
{

    /** @var bool $isCooldown */
    private $isCooldown = false;

    /**
     * @param int $currentTick
     */
    public function onRun($currentTick)
    {
        if ($this->isCooldown) {
            $this->cancel();
        }
    }

    public function cancel()
    {
        if ($this->getTaskId() != null) {
            Server::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        }
    }

    /**
     * @param int $ticks
     */
    public function registerToRepeat(int $ticks = 20)
    {
        Server::getInstance()->getScheduler()->scheduleRepeatingTask($this, $ticks);
    }

    /**
     * @param int $ticks
     */
    public function registerAfter(int $ticks)
    {
        $this->isCooldown = true;
        Server::getInstance()->getScheduler()->scheduleDelayedTask($this, $ticks);
    }
}