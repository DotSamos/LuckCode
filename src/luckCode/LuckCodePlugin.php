<?php

declare(strict_types=1);

namespace luckCode;

use luckCode\data\manager\types\LuckDataManager;
use luckCode\data\save\manager\DataSaveWorker;
use luckCode\database\types\LuckDatabase;
use luckCode\scheduler\loader\DatabaseLoaderWaitTask;
use luckCode\utils\ProviderLoader;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;

class LuckCodePlugin extends PluginBase
{

    const PREFIX = '§f[§l§3L§5C§r§f] ';

    /** @var LuckDataManager $dataManager */
    private $dataManager;

    /** @var LuckDatabase $database */
    private $database;

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
        $this->dataManager = ($dataManager = $this->loadDataManager());
        (new DatabaseLoaderWaitTask())->registerToRepeat(1);

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
        $database = $this->database;
        if($database) $database->getProvider()->close();
    }

    /** @return LuckDataManager */
    public function getDataManager() : LuckDataManager {
        return $this->dataManager;
    }

    private function loadDataManager() : LuckDataManager {
        return $this->dataManager = new LuckDataManager();
    }

    public function loadDatabase() {
        if(!$this->database) {
            $providerData = $this->dataManager->get('database')->getContents();
            $provider = (new ProviderLoader($this, $providerData['type_priority'], $providerData['mysqli_auth']))->get();
            if($provider == null) {
                $this->getLogger()->info('§cNenhum provedor de dados pode ser inicializado!');
                $this->getServer()->getPluginManager()->disablePlugin($this);
            } else {
                $this->database = new LuckDatabase($provider, $this);
            }
        }
    }
}