<?php

declare(strict_types=1);

namespace luckCode;

use function implode;
use luckCode\command\defaults\LuckCodeCommand;
use luckCode\data\manager\types\LuckDataManager;
use luckCode\data\save\manager\DataSaveWorker;
use luckCode\database\types\LuckDatabase;
use luckCode\entity\EntityManager;
use luckCode\menu\manager\MenuController;
use luckCode\menu\tile\MenuChestTile;
use luckCode\scheduler\loader\DatabaseLoaderWaitTask;
use luckCode\utils\ProviderLoader;
use luckCode\utils\text\TextFormatter;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\tile\Tile;

class LuckCodePlugin extends PluginBase
{

    const PREFIX = '§f[§l§3L§5C§r§f] ';
    const VERSION = 0.1;
    const ADMIN_PERMISSION = 'luckcode.admin';

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
        EntityManager::registerDefaults();
        (new LuckCodeCommand())->registerCommand($this, 'samos.luckcode.command');
        Tile::registerTile(MenuChestTile::class);

        Server::getInstance()->getLogger()->info(TextFormatter::center(implode("§r\n", [
            '§8',
            '§8',
            '§ePlugin LuckCode v0.1',
            '§f"A minha querida caixa de ferramentas"',
            '§8',
            '§bBy @SamosMC 2021',
            '§8'
        ])));
    }

    public function onDisable()
    {
        MenuController::closeAll('§cMenu fechado devido a um evento inesperado. Tente reabrir ele novamente dentro de alguns segundos...');
        DataSaveWorker::startWorker();
        $database = $this->database;
        if ($database) $database->close();
    }

    /** @return LuckDataManager */
    public function getDataManager(): LuckDataManager
    {
        return $this->dataManager;
    }

    private function loadDataManager(): LuckDataManager
    {
        return $this->dataManager = new LuckDataManager();
    }

    public function loadDatabase()
    {
        if (!$this->database) {
            $providerData = $this->dataManager->get('database')->getContents();
            $provider = (new ProviderLoader($this, $providerData['type_priority'], $providerData['mysqli_auth']))->get();
            if ($provider == null) {
                $this->getLogger()->info('§cNenhum provedor de dados pode ser inicializado!');
                $this->getServer()->getPluginManager()->disablePlugin($this);
            } else {
                $this->database = new LuckDatabase($provider, $this);
            }
        }
    }
}