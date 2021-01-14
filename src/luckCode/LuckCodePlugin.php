<?php

declare(strict_types=1);

namespace luckCode;

use luckCode\plugin\interfaces\LuckSystemLoader;
use luckCode\system\controller\SystemController;
use luckCode\system\types\FreezeTimeSystem;
use luckCode\system\types\LuckCommandSystem;
use luckCode\system\types\LuckDatabaseSystem;
use luckCode\updater\AutoUpdater;
use luckCode\command\defaults\LuckCodeCommand;
use luckCode\data\manager\types\LuckDataManager;
use luckCode\data\save\manager\DataSaveWorker;
use luckCode\database\types\LuckDatabase;
use luckCode\entity\EntityManager;
use luckCode\menu\manager\MenuController;
use luckCode\menu\tile\MenuChestTile;
use luckCode\utils\ProviderLoader;
use luckCode\utils\text\TextFormatter;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\tile\Tile;
use function implode;

class LuckCodePlugin extends PluginBase implements LuckSystemLoader
{

    const PREFIX = '§f[§l§3L§5C§r§f] ';
    const VERSION = 0.1;
    const ADMIN_PERMISSION = 'luckcode.admin';

    /** @var LuckDataManager $dataManager */
    private $dataManager;

    /** @var LuckDatabase $database */
    private $database;

    /** @var SystemController $systemController */
    private $systemController;

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
        $this->dataManager = new LuckDataManager();
        $this->systemController = ($sy = new SystemController($this));
        $sy->onLoad();
    }

    public function onEnable()
    {
        $this->loadDatabase();
        $this->systemController->onEnable();
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
        $this->systemController->onDisable();
        MenuController::closeAll('§cMenu fechado devido a um evento inesperado. Tente reabrir ele novamente dentro de alguns segundos...');
        DataSaveWorker::startWorker();
    }

    /** @return LuckDatabase|null */
    public function getDatabase()
    {
        return $this->database;
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

    private function loadBase() {
        EntityManager::registerDefaults();
        Tile::registerTile(MenuChestTile::class);
        AutoUpdater::check();
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

    /**
     * @inheritDoc
     */
    public function getSystemStatusList(): array
    {
        return $this->dataManager->get('systems')->getContents();
    }

    /**
     * @inheritDoc
     */
    public function getSystemsBases(): array
    {
        return [
            FreezeTimeSystem::class,
            LuckCommandSystem::class,
            LuckDatabaseSystem::class
        ];
    }

    /**
     * @inheritDoc
     */
    public function getSystemController(): SystemController
    {
        return $this->systemController;
    }
}