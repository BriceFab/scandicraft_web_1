<?php


namespace App\Service;

use App\Classes\Rcon;
use Exception;
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

    public function executeFactionCommand($commands): bool
    {
        $host = $this->parameter->get('rcon_faction_host');                      // Server host name or IP
        $port = (int)$this->parameter->get('rcon_faction_port');                 // Port rcon is listening on
        $password = $this->parameter->get('rcon_faction_password');              // rcon.password setting set in server.properties

        return $this->executeCommands($commands, $host, $port, $password);
    }

    private function executeCommands(array $commands, string $host, int $port, string $password): bool
    {
        try {
            $rcon = new Rcon($host, $port, $password, $this->timeout);

            if ($rcon->connect()) {
                foreach ($commands as $command) {
                    $command_success = $rcon->sendCommand($command);
                    if (!$command_success) {
                        return false;
                    }
                }

                $rcon->disconnect();

                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    private function executeCommand(string $command, string $host, int $port, string $password): bool
    {
        try {
            $rcon = new Rcon($host, $port, $password, $this->timeout);

            if ($rcon->connect()) {
                $command_success = $rcon->sendCommand($command);

                $rcon->disconnect();

                return $command_success;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

}