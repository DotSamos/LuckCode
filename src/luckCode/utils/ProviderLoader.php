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

class ProviderLoader implements InfoStatus {

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
    public function __construct(PluginBase $ownerPlugin, array $order, array $mysqliAuth, bool $ignoreError = false) {
        $this->ownerPlugin = $ownerPlugin;
        $this->mysqliAuth = $mysqliAuth;

        $this->showInfo('Procurando provedor...');

        foreach ($order as $providerName) {
            if ($this->search($providerName, $ignoreError)) break;
        }
    }

    /**
     * @param string $type
     * @param bool $ignoreError
     * @return bool
     */
    private function search(string $type, bool $ignoreError): bool {
        $type = strtolower($type);
        $ev = new SearchProviderEvent();
        $ev->call();
        try {
            $provider = $ev->getType($type);
            if (!$provider) {
                $this->showAlert('O provedor §f' . $type . '§7 não existe!');
                return false;
            }
            $pl = $this->ownerPlugin;
            $conArgs = array_merge($this->mysqliAuth, ['file' => strtolower($pl->getName()) . '.db', 'path' => $pl->getDataFolder() . 'database' . DIRECTORY_SEPARATOR]);
            /** @var IProvider $provider */
            $provider = new $provider($conArgs, $pl);
            if ($provider->failInitializeException() && !$ignoreError) return false;
            $this->found = $provider;
        } catch (Throwable $e) {
            $this->printError($e);
            return false;
        }
        return true;
    }

    /** @param string $info */
    public function showInfo(string $info) {
        $this->getLogger()->info('§7[ProviderLoader] §7' . $info);
    }

    /** @return PluginLogger */
    public function getLogger(): PluginLogger {
        return $this->ownerPlugin->getLogger();
    }

    /** @param string $alert  */
    public function showAlert(string $alert) {
        $this->getLogger()->info('§e[ProviderLoader] §7' . $alert);
    }

    /** @return IProvider|null */
    public function get() {
        return $this->found;
    }

    /** @param string $error */
    public function showError(string $error) {
        $this->getLogger()->info('§c[ProviderLoader] §7' . $error);
    }

    /** @param Throwable $error */
    public function printError(Throwable $error) {
        $this->showError(Utils::getThrowablePrint($error));
    }
}