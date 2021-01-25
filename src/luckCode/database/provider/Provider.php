<?php

declare(strict_types=1);

namespace luckCode\database\provider;

use luckCode\database\provider\exceptions\ProviderInitializeException;
use luckCode\utils\InfoStatus;
use luckCode\utils\Utils;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\PluginLogger;
use Throwable;
use function is_numeric;
use function str_replace;
use function substr;
use function wordwrap;

abstract class Provider implements InfoStatus, interfaces\IProvider {

    /** @var PluginBase $ownerPlugin */
    protected $ownerPlugin;

    /** @var Throwable $lastError */
    protected $lastError;

    /** @var ProviderInitializeException|null $initializeException */
    protected $initializeException;

    protected $connection;

    /**
     * Provider constructor.
     * @param array $conArgs
     * @param PluginBase $owner
     */
    public function __construct(array $conArgs, PluginBase $owner) {
        $this->ownerPlugin = $owner;
        if (!$this->tryConnect($conArgs)) {
            $this->initializeException = new ProviderInitializeException($this->lastError->getMessage(), $this->lastError->getCode());
        }
    }

    /** @return bool */
    public function close(): bool {
        $this->showInfo('A conexão foi finalizada pelo sistema!');
        return true;
    }

    /** @param string $info */
    public function showInfo(string $info) {
        $this->getLogger()->info('§7[Provider(§a' . $this->getType() . '§7)] §7' . $info);
    }

    /** @return PluginLogger */
    public function getLogger(): PluginLogger {
        return $this->ownerPlugin->getLogger();
    }

    /** @return PluginBase */
    public function getOwnerPlugin(): PluginBase {
        return $this->ownerPlugin;
    }

    /** @param string $alert */
    public function showAlert(string $alert) {
        $this->getLogger()->info('§e[Provider(§a' . $this->getType() . '§e)] §7' . $alert);
    }

    /** @param Throwable $error */
    public function printError(Throwable $error) {
        $this->showError(Utils::getThrowablePrint($error));
    }

    /** @param string $error */
    public function showError(string $error) {
        $this->getLogger()->info('§c[Provider(§a' . $this->getType() . '§c)] §7' . $error);
    }

    public function getRawConnection() {
        return $this->connection;
    }

    /** @return Throwable|null */
    public function fail() {
        return $this->lastError;
    }

    /** @return ProviderInitializeException|null */
    public function failInitializeException() {
        return $this->initializeException;
    }
}