<?php
namespace Laventure\Component\Database\Connection;



use Laventure\Component\Database\Connection\Configuration\ConfigurationBag;
use Laventure\Component\Database\Connection\Contract\ConnectionInterface;
use Laventure\Component\Database\Connection\Drivers\PDO\Contract\PdoConnectionInterface;

/**
 * @ConnectionFactory
*/
class ConnectionFactory
{


    /**
     * @var ConnectionBag
    */
    public $connections;





    /**
     * @var ConfigurationBag
    */
    public $configs;




    /**
     * @param array $credentials
    */
    public function __construct(array $credentials = [])
    {
          $this->connections = new ConnectionBag();
          $this->configs     = new ConfigurationBag($credentials);
    }




    /**
     * Create a connection
     *
     * @param $name
     * @param array $config
     * @return ConnectionInterface
     */
    public function makeConnection($name, array $config): ConnectionInterface
    {
        if ($connection = $this->connections->get($name)) {
             $connection->connect($config);
        }

        if (! $connection->connected()) {
            trigger_error("Could not connect to {$connection->getName()} database.");
        }

        return $connection;
    }



    /**
     * @param string $name
     * @return PdoConnectionInterface
    */
    public function getPdoConnection(string $name): PdoConnectionInterface
    {
         $connection = $this->connections->get($name);

         if (! $connection instanceof PdoConnectionInterface) {
              trigger_error("current connection {$name} not implement PdoConnectionInterface inside method : ". __METHOD__);
         }

         return $connection;
    }





    /**
     * @param $name
     * @return void
    */
    public function removeConnection($name)
    {
         $this->configs->remove($name);
         $this->connections->remove($name);
    }
}