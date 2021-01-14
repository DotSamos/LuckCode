<?php

declare(strict_types=1);

namespace luckCode\scheduler\updater;

use pocketmine\scheduler\AsyncTask;
use pocketmine\utils\Config;
use pocketmine\utils\Utils;
use pocketmine\Server;
use Throwable;
use function is_array;
use function is_null;
use function version_compare;
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
        try {
            $data = @yaml_parse(Config::fixYAMLIndexes(Utils::getURL(self::URL)));

            $value = is_null($data) ? 'error' : strcmp(((string)$this->version), ((string)$data['version']));

            if (!is_null($data) && is_array($data)) {
                $this->setResult(['update' => $value, 'version' => $data['version']]);
            } else {
                $this->setResult(['update' => 'error']);
            }
        } catch (Throwable $e) {
            $this->setResult(['update' => 'error']);
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
        } else if($this->getResult()['update'] == -1) {
             $message = '§r§6Uma nova versão do LuckCode está disponível em §fhttps://github.com/SamosMC/LuckCode §7[v'.$result['version'].']';
        }
        if(isset($message)) $server->getPluginManager()->getPlugin('LuckCode')->getLogger()->info($message);
    }
}
