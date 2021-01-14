<?php

declare(strict_types=1);

namespace luckCode\scheduler\updater;

use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\Utils;
use Throwable;
use function is_array;
use function version_compare;
use function yaml_parse;

class LuckUpdater extends AsyncTask
{

    const URL = 'https://raw.githubusercontent.com/SamosMC/LuckCode/main/plugin.yml';

    /** @var string $version */
    private $version;

    /**
     * LuckUpdater constructor.
     * @param float $version
     */
    public function __construct(float $version)
    {
        $this->version = $version;
    }

    public function onRun()
    {
        try {
            $data = @yaml_parse(Config::fixYAMLIndexes(Utils::getURL(self::URL, 120)));

            if (!is_array($data) || !isset($data['version'])) {
                $this->setResult(['update' => 'error']);
                return;
            }

            $value = version_compare(((string)$this->version), ((string)$data['version']));

            $this->setResult(['update' => $value, 'version' => $data['version']]);

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

        if ($result['update'] === 'error') {
            $message = '§r§eAcho que vem uma nova versão do LuckCode por ai! Fica esperto oO';
        } else if ($this->getResult()['update'] == -1) {
            $message = '§r§6Uma nova versão do LuckCode está disponível em §fhttps://github.com/SamosMC/LuckCode §7[v' . $result['version'] . ']';
        }
        if (isset($message)) $server->getPluginManager()->getPlugin('LuckCode')->getLogger()->info($message);

    }
}