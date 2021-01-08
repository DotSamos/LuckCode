<?php

declare(strict_types=1);

namespace luckCode\utils;

use luckCode\database\provider\interfaces\IProvider;
use luckCode\listener\provider\SearchProviderEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\PluginLogger;
use Throwable;
use function array_merge;
use function strtolower;
use const DIRECTORY_SEPARATOR;

class ProviderLoader implements InfoStatus
{

    /** @var PluginBase $ownerPlugin */
    private $ownerPlugin;

    /** @var array $mysqliAuth */
    private $mysqliAuth;

    /** @var IProvider|null $found */
    private $found;

    /**
     * ProviderLoader constructor.
     * @param PluginBase $ownerPlugin
     * @param array $order
     * @param array $mysqliAuth
     * @param bool $ignoreError
     */
    public function __construct(PluginBase $ownerPlugin, array $order, array $mysqliAuth, bool $ignoreError = false)
    {
        $this->ownerPlugin = $ownerPlugin;
        $this->mysqliAuth = $mysqliAuth;

        $this->showInfo('Procurando provedor...');

        foreach ($order as $providerName) {
            if($this->search($providerName, $ignoreError)) break;
        }
    }

    /**
     * @param string $type
     * @param bool $ignoreError
     * @return bool
     */
    private function search(string $type, bool $ignoreError) : bool {
        $ev = new SearchProviderEvent();
        $ev->call();
        $provider = $ev->getType($type);
        if(!$provider) {
            $this->showAlert('O provedor §f'.$type.'§7 não existe!');
            return false;
        }
        $pl = $this->ownerPlugin;
        $conArgs = array_merge($this->mysqliAuth, ['file' => strtolower($pl->getName()).'.db', 'path' => $pl->getDataFolder().'database'.DIRECTORY_SEPARATOR]);
        /** @var IProvider $provider */
        $provider = new $provider($conArgs, $pl);
        if($provider->failInitializeException() && !$ignoreError) return false;
        $this->found = $provider;
        return true;
    }

    /** @return IProvider|null */
    public function get() {
        return $this->found;
    }

    /**
     * @inheritDoc
     */
    public function getLogger(): PluginLogger
    {
        return $this->ownerPlugin->getLogger();
    }

    /**
     * @inheritDoc
     */
    public function showInfo(string $info)
    {
        $this->getLogger()->info('§7[ProviderLoader] §7'.$info);
    }

    /**
     * @inheritDoc
     */
    public function showAlert(string $alert)
    {
        $this->getLogger()->info('§e[ProviderLoader] §7'.$alert);
    }

    /**
     * @inheritDoc
     */
    public function showError(string $error)
    {
        $this->getLogger()->info('§c[ProviderLoader] §7'.$error);
    }

    /**
     * @inheritDoc
     */
    public function printError(Throwable $error)
    {}
}