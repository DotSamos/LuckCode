<?php

declare(strict_types=1);

namespace luckCode\database\table;

use luckCode\database\provider\interfaces\IProvider;
use luckCode\utils\InfoStatus;
use luckCode\utils\Utils;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\PluginLogger;
use Throwable;
use function implode;
use function is_numeric;
use function str_replace;
use function strpos;
use function substr;
use function wordwrap;

abstract class Table implements interfaces\ITable, InfoStatus {

    /** @var PluginBase $ownerPlugin */
    protected $ownerPlugin;

    /** @var IProvider $provider */
    protected $provider;

    /** @var bool $isInitialized */
    protected $isInitialized = false;

    /**
     * Table constructor.
     * @param IProvider $provider
     * @param PluginBase $ownerPlugin
     */
    public function __construct(IProvider $provider, PluginBase $ownerPlugin) {
        $this->ownerPlugin = $ownerPlugin;
        $this->provider = $provider;
        $this->tryCreateTable($this->getCreationExecute());
    }

    /**
     * @param string $execute
     * @return bool
     */
    public function tryCreateTable(string $execute): bool {
        $provider = $this->provider;
        if (!$provider->exec($execute)) {
            $this->showError('Não foi possivel inicializar a tabela:');
            $this->showError('§f' . (($fail = $provider->fail()) instanceof Throwable ? $fail->getMessage() : 'No error description :('));
            return false;
        } else {
            $this->isInitialized = true;
            return true;
        }
    }

    /** @param string $error */
    public function showError(string $error) {
        $this->getLogger()->info('§c[Table(§f' . $this->name() . '§c)] ' . $error);
    }

    /** @return PluginLogger */
    public function getLogger(): PluginLogger {
        return $this->ownerPlugin->getLogger();
    }

    /** @return string */
    public function name(): string {
        return static::NAME;
    }

    /** @return bool */
    public function isInitialized(): bool {
        return $this->isInitialized();
    }

    /** @return PluginBase */
    public function getPluginOwner(): PluginBase {
        return $this->ownerPlugin;
    }

    /** @param string $info */
    public function showInfo(string $info) {
        $this->getLogger()->info('§7[Table(§f' . $this->name() . '§7)] ' . $info);
    }

    /** @param string $alert */
    public function showAlert(string $alert) {
        $this->getLogger()->info('§e[Table(§f' . $this->name() . '§e)] ' . $alert);
    }

    /** @param Throwable $error */
    public function printError(Throwable $error) {
        $this->showError(Utils::getThrowablePrint($error));
    }
}