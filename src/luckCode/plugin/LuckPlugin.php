<?php

declare(strict_types=1);

namespace luckCode\plugin;

use luckCode\data\manager\DataManager;
use luckCode\database\types\LuckDatabase;
use luckCode\listener\database\LuckDatabaseEnableEvent;
use luckCode\listener\database\LuckDatabaseNotInitializeEvent;
use luckCode\plugin\interfaces\LuckDatabaseRequire;
use luckCode\plugin\interfaces\LuckDataManagerRequire;
use luckCode\plugin\interfaces\LuckSystemLoader;
use luckCode\system\controller\SystemController;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use function array_walk;

abstract class LuckPlugin extends PluginBase implements Listener
{

    /** @var DataManager $dataManager */
    protected $dataManager;

    /** @var LuckDatabase $database */
    protected $database;

    /** @var SystemController $systemController */
    protected $systemController;

    public function onLoad()
    {
        if(isset(self::$instance)) {
            self::$instance = $this;
        }
        $this->checkDataManager();
        $this->checkSystemLoader();
    }

    public function onEnable()
    {
        $this->checkProvider();
        if($this instanceof LuckSystemLoader) {
            $this->systemController->onEnable();
        }
    }

    public function onDisable()
    {
        if($this instanceof LuckSystemLoader) {
            $this->systemController->onDisable();
        }
    }

    /** @return LuckDatabase */
    public function getDatabase() : LuckDatabase {
        return $this->database;
    }

    /** @return SystemController */
    public function getSystemController() : SystemController {
        return $this->systemController;
    }

    protected function checkDataManager() {
        if($this instanceof LuckDataManagerRequire) {
            $class = $this->getBaseDataManager();
            $this->dataManager = new $class();
        }
    }

    protected function checkProvider() {
        if($this instanceof LuckDatabaseRequire) {
            $this->getServer()->getPluginManager()->registerEvents($this, $this);
        }
    }

    protected function checkSystemLoader()
    {
        if($this instanceof LuckSystemLoader) {
            $class = $this->getSystemControllerBase();
            /** @var SystemController $sc */
            $sc = new $class($this);
            $this->systemController = $sc;
            $sc->onLoad();
        }
    }

    /** @param LuckDatabaseEnableEvent $e */
    public function onConnectDatabase(LuckDatabaseEnableEvent $e) {
        if($this instanceof LuckDatabaseRequire) {
            $db = $e->getDatabase();
            $this->database = $db;
            $provider = $db->getProvider();
            $tables = $this->getBaseTables();
            array_walk($tables, function (string $class) use($provider, $db){
                $db->addTable(new $class($provider, $this));
            });
        }
    }

    /** @param LuckDatabaseNotInitializeEvent $e */
    public function onDatabaseConnectionError(LuckDatabaseNotInitializeEvent $e) {
        if($this instanceof LuckDatabaseRequire) {
            $this->getLogger()->info('§cO provedor de dados do LuckCode não pode ser carregado!');
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }
    }
}