<?php


namespace App\Service;

use App\Classes\Rcon;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class RconService
 * @package App\Service
 */
class RconService
{
    private $parameter;
    private $timeout;

    public function __construct(ParameterBagInterface $parameter)
    {
        $this->parameter = $parameter;

        $this->timeout = $parameter->get('rcon_timeout');
    }

    public function executeFactionCommand($command): bool
    {
        $host = $this->parameter->get('rcon_faction_host');                      // Server host name or IP
        $port = (int)$this->parameter->get('rcon_faction_port');                 // Port rcon is listening on
        $password = $this->parameter->get('rcon_faction_password');              // rcon.password setting set in server.properties

        return $this->executeCommand($command, $host, $port, $password);
    }

    private function executeCommand(string $command, string $host, int $port, string $password): bool
    {
        $rcon = new Rcon($host, $port, $password, $this->timeout);

        if ($rcon->connect()) {
            $command_success = $rcon->sendCommand($command);

            $rcon->disconnect();

            return $command_success;
        } else {
            return false;
        }
    }

}