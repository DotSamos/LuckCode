<?php

declare(strict_types=1);

namespace luckCode\entity\holographic;

use luckCode\LuckCodePlugin;
use luckCode\utils\text\TextFormatter;
use pocketmine\Player;
use function array_walk;
use function str_repeat;
use function strlen;
use function substr;

class LuckHolographicEntity extends LuckHolographicEntityBase
{

    /** @var int $typeBar */
    private $nextColor = 0;

    /** @var bool $isComplete */
    private $isComplete = false;

    public function onUpdate($tick)
    {
        $bar = str_repeat('-', 16);
        if($this->nextColor++ == strlen($bar)) {
            $this->nextColor = 0;
            $this->isComplete = $this->isComplete ? false : true;
        }
        $barFront = substr($bar, $this->nextColor);
        $barRedo = substr($bar, strlen($bar) - $this->nextColor);
        $bar = '§f'.$barFront.'§6--§f'.$barRedo;
        $redoBar = '§f'.$barRedo.'§6--§f'.$barFront;
        $text = ($this->isComplete ? $bar : $redoBar)."\n§r§l§3Luck§5Code§r \n§7v".LuckCodePlugin::VERSION."\n".($this->isComplete ? $redoBar : $bar);
        $text = TextFormatter::center($text);

        array_walk($this->hasSpawned, function (Player $p) use($text) {
           $this->setNameTagFor($p, $text);
        });

        return parent::onUpdate($tick);
    }
}