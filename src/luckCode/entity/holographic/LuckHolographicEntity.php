<?php

declare(strict_types=1);

namespace luckCode\entity\holographic;

use luckCode\LuckCodePlugin;
use pocketmine\Player;
use function array_walk;
use function str_repeat;
use function strlen;
use function substr;

class LuckHolographicEntity extends LuckHolographicEntityBase
{

    /** @var int $typeBar */
    private $nextColor = 0;

    public function onUpdate($tick)
    {
        $bar = str_repeat('-', 12);
        if($this->nextColor++ == strlen($bar)) {
            $this->nextColor = 0;
        }
        $barFront = substr($bar, $this->nextColor);
        $barRedo = substr($bar, strlen($bar) - $this->nextColor);
        $bar = '§f'.$barFront.'§b--§f'.$barRedo;
        $redoBar = '§f'.$barRedo.'§b--§f'.$barFront;
        $text = $bar."\n§r§l§3 Luck§5Code§r §7v".LuckCodePlugin::VERSION."\n".$redoBar;

        array_walk($this->hasSpawned, function (Player $p) use($text) {
           $this->setNameTagFor($p, $text);
        });

        return parent::onUpdate($tick);
    }
}