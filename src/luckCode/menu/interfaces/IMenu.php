<?php

namespace luckCode\menu\interfaces;

use luckCode\menu\tile\MenuChestTile;
use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\Player;

interface IMenu
{

    const IS_CREATIVE = '§cVocê não pode abrir este menu estando no modo criativo!';
    const HAS_TILE = '§cNão é possivel abrir o menu em sua posição atual. Não fique com báus (ou outros blocos com menus) sobre sua cabeça ou pés!';
    const BAD_POSITION = '§cVocê não pode abrir um menu nesta posição!';

    /**
     * @param Player $p
     * @param Block $block
     */
    public function sendBlock(Player $p, Block $block);

    /**
     * @param Position $pos
     * @param Player $p
     * @param string $name
     * @return MenuChestTile
     */
    public function makeTile(Position $pos, Player $p, string $name): MenuChestTile;

    /**
     * @param Player $p
     * @return Item[]
     */
    public function getItems(Player $p): array;

    /**
     * @param array $items
     */
    public function setItems(array $items);

    /**
     * @param Player $p
     * @param Item $item
     * @return bool
     */
    public function processClick(Player $p, Item $item): bool;
}