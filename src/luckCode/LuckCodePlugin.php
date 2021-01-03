<?php

declare(strict_types=1);

namespace luckCode;

use luckCode\data\save\manager\DataSaveWorker;
use luckCode\data\types\YamlData;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;

class LuckCodePlugin extends PluginBase
{

    const PREFIX = '§f[§6Luck§5Code§f] ';

    /** @var LuckCodePlugin $instance */
    private static $instance;

    /** @return LuckCodePlugin */
    public static function getInstance(): LuckCodePlugin
    {
        return self::$instance;
    }

    public function onLoad()
    {
        self::$instance = $this;
    }

    public function onEnable()
    {

        // Eh... Peguei o console do servidor só por estetica mesmo '-'
        Server::getInstance()->getLogger()->info(implode("§r\n", [
            '§8',
            '§8',
            '§e         Plugin LuckCode v0.1',
            '§f"A minha querida caixa de ferramentas"',
            '§8',
            '§b          By @SamosMC 2021',
            '§8'
        ]));
    }

    public function onDisable()
    {
        DataSaveWorker::startWorker();
    }
}