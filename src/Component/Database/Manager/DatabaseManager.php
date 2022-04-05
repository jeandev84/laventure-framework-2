<?php
namespace Laventure\Component\Database\Manager;


use Laventure\Component\Database\Connection\ConnectionFactory;
use Laventure\Component\Database\Connection\Contract\ConnectionInterface;
use Laventure\Component\Database\Manager\Contract\DatabaseManagerInterface;


/**
 * @DatabaseManager
*/
class DatabaseManager implements DatabaseManagerInterface
{


    /**
     * Current connection
     *
     * @var string
    */
    protected $connection;




    /**
     * @var array
    */
    protected $status = [];





    /**
     * @var ConnectionFactory
    */
    public $factory;




    /**
     * @param array $credentials
    */
    public function __construct(array $credentials = [])
    {
          $this->factory = new ConnectionFactory($credentials);
    }




    /**
     * @inheritDoc
    */
    public function connect($name, array $config)
    {
         if (! $this->connection) {
             $this->setCurrentConnection($name);
             $this->setConfigurations($config);
         }
    }



    /**
     * Set current connection
     *
     * @param string $connection
     * @return $this
    */
    public function setCurrentConnection(string $connection): self
    {
           $this->connection = $connection;

           return $this;
    }




    /**
     * Get current connection
     *
     * @return string
    */
    public function getCurrentConnection(): string
    {
         return $this->connection;
    }





    /**
     * Add connection
     *
     * @param ConnectionInterface $connection
     * @return $this
    */
    public function setConnection(ConnectionInterface $connection): self
    {
          $this->factory->connections->add($connection);

           return $this;
    }




    /**
     * Add connection stack
     *
     * @param ConnectionInterface[] $connections
     * @return $this
    */
    public function setConnections(array $connections): self
    {
        foreach ($connections as $connection) {
            $this->setConnection($connection);
        }

        return $this;
    }





    /**
     * Add connection credentials
     *
     * @param $name
     * @param $credentials
     * @return $this
    */
    public function setConfiguration($name, $credentials): self
    {
         $this->factory->configs->add($name, $credentials);

         return $this;
    }




    /**
     * Add connections credentials
     *
     * @param array $credentials
     * @return $this
    */
    public function setConfigurations(array $credentials): self
    {
        $this->factory->configs->merge($credentials);

        return $this;
    }




    /**
     * get connection configuration credentials by name
     *
     * @param string $name
     * @return array
    */
    public function configuration(string $name): array
    {
        if (! $this->factory->configs->has($name)) {
            trigger_error("Could not detect credentials for connection '{$name}'");
        }

        return $this->factory->configs->get($name);
    }






    /**
     * @inheritDoc
    */
    public function connection($name = null)
    {
          $name = $name ?? $this->getCurrentConnection();

          $config = $this->configuration($name);

          if (! $this->factory->connections->has($name)) {
               trigger_error("Unavailable connection name : '{$name}'");
          }

          $connection = $this->factory->makeConnection($name, $config);

          $this->setConnectionStatus($connection);
          $this->setCurrentConnection($name);

          return $connection;
    }




    /**
     * @inheritDoc
    */
    public function disconnect($name = null)
    {
        if ($this->factory->connections->has($name)) {
            $connection = $this->factory->connections->get($name);
            return $connection->disconnect();
        }

        return $this->connection()->disconnect();
    }





    /**
     * @inheritDoc
    */
    public function reconnect($name = null)
    {
        if ($this->factory->connections->has($name)) {
            $connection =  $this->connection($name);
            if ($connection->hasDatabase()) {
                $this->connection();
            }
        }
    }




    /**
     * @param string|null $name
    */
    public function purge(string $name = null)
    {
        $this->disconnect($name);

        $this->factory->connections->remove($name);
    }





    /**
     * @return array
    */
    public function getConnections(): array
    {
        return $this->factory->connections->all();
    }




    /**
     * @return array
    */
    public function getConfigurations(): array
    {
        return $this->factory->configs->all();
    }




    /**
     * @param string $name
    */
    public function removeConnection(string $name)
    {
        $this->factory->removeConnection($name);
    }




    /**
     * @param ConnectionInterface $connection
     * @return void
    */
    protected function setConnectionStatus(ConnectionInterface $connection)
    {
         $this->status[$connection->getName()] = $connection->connected();
    }



    /**
     * Get connection status
     *
     * @param $name
     * @return bool
    */
    public function getConnectionStatus($name): bool
    {
        return $this->status[$name] ?? false;
    }
}