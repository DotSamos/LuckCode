<?php

declare(strict_types=1);

namespace luckCode\database\table;

use luckCode\database\provider\interfaces\IProvider;
use luckCode\utils\InfoStatus;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\PluginLogger;
use Throwable;
use function implode;
use function strpos;
use function substr;

abstract class Table implements interfaces\ITable, InfoStatus
{

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
    public function __construct(IProvider $provider, PluginBase $ownerPlugin)
    {
        $this->ownerPlugin = $ownerPlugin;
        $this->provider = $provider;
        $this->tryCreateTable($this->getCreationExecute());
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return static::NAME;
    }

    /**
     * @inheritDoc
     */
    public function isInitialized(): bool
    {
        return $this->isInitialized();
    }

    /**
     * @inheritDoc
     */
    public function getPluginOwner(): PluginBase
    {
        return $this->ownerPlugin;
    }

    /**
     * @inheritDoc
     */
    public function tryCreateTable(string $execute): bool
    {
        $provider = $this->provider;
        if(!$provider->exec($execute)) {
            $this->showError('Não foi possivel inicializar a tabela:');
            $this->showError('§f'.(($fail = $provider->fail()) instanceof Throwable ? $fail->getMessage() : 'No error description :('));
            return false;
        } else {
            $this->isInitialized = true;
            return true;
        }
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
        $this->getLogger()->info('§7[Table(§a'.$this->name().'§7)] '.$info);
    }

    /**
     * @inheritDoc
     */
    public function showAlert(string $alert)
    {
        $this->getLogger()->info('§e[Table(§a'.$this->name().'§e)] '.$alert);
    }

    /**
     * @inheritDoc
     */
    public function showError(string $error)
    {
        $this->getLogger()->info('§c[Table(§a'.$this->name().'§c)] '.$error);
    }

    /**
     * @inheritDoc
     */
    public function printError(Throwable $error)
    {
        $this->showError(implode("§r\n", [
            '§7'.$error->getMessage().'§4('.$error->getCode().')',
            "§c+-> §aIn line §f{$error->getLine()}§a from:",
            "§c+-> §e".substr($error->getFile(), strpos($error->getFile(), 'luckCode')),
            "§8"
        ]));
    }
}