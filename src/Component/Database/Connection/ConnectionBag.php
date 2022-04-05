<?php
namespace Laventure\Component\Database\Connection;


use Laventure\Component\Database\Connection\Contract\ConnectionInterface;
use Laventure\Component\Database\Connection\Drivers\PDO\Connectors\MysqlConnection;
use Laventure\Component\Database\Connection\Drivers\PDO\Connectors\OracleConnection;
use Laventure\Component\Database\Connection\Drivers\PDO\Connectors\PgsqlConnection;
use Laventure\Component\Database\Connection\Drivers\PDO\Connectors\SqliteConnection;
use Laventure\Component\Database\Connection\Drivers\PDO\Contract\PdoConnectionInterface;


/**
 * @ConnectionBag
 */
class ConnectionBag
{

    const SQLITE = 'sqlite';
    const MYSQL  = 'mysql';
    const PGSQL  = 'pgsql';
    const ORACLE = 'oci';


    /**
     * @var array
    */
    protected $connections = [];



    /**
     * ConnectionCollection constructor
    */
    public function __construct()
    {
         $this->addConnections($this->getDefaultConnections());
    }



    /**
     * @param ConnectionInterface $connection
     * @return ConnectionInterface
    */
    public function add(ConnectionInterface $connection): ConnectionInterface
    {
         $this->connections[$connection->getName()] = $connection;

         return $connection;
    }




    /**
     * @param array $connections
     * @return void
    */
    public function addConnections(array $connections)
    {
        foreach ($connections as $connection) {
            $this->add($connection);
        }
    }



    /**
     * @param $name
     * @param null $default
     * @return ConnectionInterface|null
    */
    public function get($name, $default = null): ?ConnectionInterface
    {
        return $this->connections[$name] ?? $default;
    }





    /**
     * @param $name
     * @return bool
    */
    public function has($name): bool
    {
        return isset($this->connections[$name]);
    }





    /**
     * @return ConnectionInterface[]
    */
    public function all(): array
    {
        return $this->connections;
    }




    /**
     * @param $name
     * @return void
    */
    public function remove($name)
    {
        unset($this->connections[$name]);
    }



    /**
     * @return string[]
    */
    public function getDefaultNames(): array
    {
          $connections = [];

          foreach ($this->getDefaultConnections() as $connection) {
               $connections[$connection->getName()] = $connection;
          }

          return $connections;
    }


    /**
     * @param ConnectionInterface $connection
     * @return bool
    */
    public static function isPdoConnection(ConnectionInterface $connection): bool
    {
          return $connection instanceof PdoConnectionInterface;
    }




    /**
     * @param ConnectionInterface $connection
     * @return bool
    */
    public static function isMysqlConnection(ConnectionInterface $connection): bool
    {
          return $connection instanceof MysqlConnection;
    }



    /**
     * @param ConnectionInterface $connection
     * @return bool
    */
    public static function isPgsqlConnection(ConnectionInterface $connection): bool
    {
         return $connection instanceof PgsqlConnection;
    }




    /**
     * @return PdoConnectionInterface[]
    */
    protected function getDefaultConnections(): array
    {
        return [
            new MysqlConnection(),
            new PgsqlConnection(),
            new SqliteConnection(),
            new OracleConnection(),
        ];
    }
}