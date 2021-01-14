<?php

declare(strict_types=1);

namespace luckCode\scheduler\updater;

use pocketmine\scheduler\AsyncTask;
use pocketmine\utils\Utils;
use pocketmine\Server;
use function yaml_parse;

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
        $data = yaml_parse(Utils::getURL(self::URL));

        $value = is_null($data) ? 'error' : version_compare($this->version, $data['version']);

        if (!is_null($data)) {
            $this->setResult(['update' => $value]);
        }
    }

    /**
     * @param Server $server
     */
    public function onCompletion(Server $server)
    {
        $result = $this->getResult();

        if (isset($result['error'])) {
            $message = '§r§cOcorreu um erro ao realizar a requesitação dos dados!';
        } else {
             $message = $this->getResult()['update'] ? '§r§cUma nova versão do LuckCode disponivel.' : '§r§eNehuma versão nova encontrada.';
        }
        $server->getLogger()->info($message);
    }
}