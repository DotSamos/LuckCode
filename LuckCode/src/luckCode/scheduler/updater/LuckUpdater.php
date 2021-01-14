<?php

declare(strict_types=1);

namespace luckCode\scheduler\updater;

use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use function file_get_contents;

class LuckUpdater extends AsyncTask
{

    const URL = 'https://raw.githubusercontent.com/SamosMC/LuckCode/main/plugin.yml';

    /** @var string $version */
    private $version;

    /**
     * LuckUpdater constructor.
     * @param string $version
     */
    public function __construct(string $version)
    {
        $this->version = $version;
    }

    public function onRun()
    {
        $data = file_get_contents(self::URL);
        var_dump($data);
        $value = $data['version'] != $this->version ? 'true' : 'false';

        $this->setResult(['update' => (bool)$value]);
    }

    /**
     * @param Server $server
     */
    public function onCompletion(Server $server)
    {
        $message = $this->getResult()['update'] ? '§r§cUma nova versão do LuckCode disponivel.' : '§r§eNehuma versão nova encontrada.';
        $server->getLogger()->info($message);
    }
}