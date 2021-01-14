<?php

declare(strict_types=1);

namespace luckCode\scheduler\updater;

use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use function file_get_contents;

class LuckUpdater extends AsyncTask
{

    const URL = 'https://raw.githubusercontent.com/SamosMC/LuckCode/main/plugin.yml';

    /** @var mixed $version */
    private $version;

    /**
     * LuckUpdater constructor.
     * @param mixed $version
     */
    public function __construct($version)
    {
        $this->version = $version;
    }

    public function onRun()
    {
        $data = yaml_parse_file(self::URL);

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