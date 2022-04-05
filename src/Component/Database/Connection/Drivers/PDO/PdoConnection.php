<?php
namespace Laventure\Component\Database\Connection\Drivers\PDO;


use Closure;
use Laventure\Component\Database\Connection\Configuration\ConfigurationBag;
use Laventure\Component\Database\Connection\Contract\QueryInterface;
use Laventure\Component\Database\Connection\Drivers\PDO\Contract\PdoConnectionInterface;
use Laventure\Component\Database\Connection\Drivers\PDO\Statement\Query;
use Laventure\Component\Database\Connection\IConnection;
use PDO;
use PDOException;


/**
 * @PdoConnection
*/
class PdoConnection extends IConnection implements PdoConnectionInterface
{



    /**
     * @inheritDoc
    */
    public function getName(): string
    {
         return "pdo";
    }



    /**
     * @inheritDoc
    */
    public function connect($config)
    {
        $config = $this->parseCredentials($config);

        $this->setConnection(
            $this->makePdo($config['driver'], $this->getCredentials($config))
        );


        $this->reconnect($config);
    }




    /**
     * @inheritDoc
    */
    public function connected(): bool
    {
        return $this->connection instanceof PDO;
    }




    /**
     * @inheritDoc
    */
    public function getConnection(): PDO
    {
         return $this->getPdo();
    }




    /**
     * @inheritDoc
    */
    public function beginTransaction()
    {
        return $this->getPdo()->beginTransaction();
    }




    /**
     * @inheritDoc
    */
    public function commit()
    {
        return $this->getPdo()->commit();
    }



    /**
     * @inheritDoc
    */
    public function rollback()
    {
        return $this->getPdo()->rollBack();
    }



    /**
     * @inheritDoc
    */
    public function lastInsertId(): int
    {
        return $this->getPdo()->lastInsertId();
    }



    /**
     * @return QueryInterface
    */
    public function createNativeQuery(): QueryInterface
    {
         return new Query($this->getPdo());
    }



    /**
     * @param $sql
     * @param array $params
     * @return QueryInterface
    */
    public function query($sql, array $params = []): QueryInterface
    {
         $statement = $this->createNativeQuery();
         $statement->prepare($sql, $params);

         return $statement;
    }




    /**
     * @inheritDoc
    */
    public function exec($sql): bool
    {
        return $this->getPdo()->exec($sql);
    }




    /**
     * @inheritDoc
    */
    public function disconnect()
    {
         $this->connection = null;
    }




    /**
     * @return PDO
    */
    public function getPdo(): PDO
    {
        if (! $this->connected()) {
            trigger_error("unable PDO connection.");
        }

        return $this->connection;
    }




    /**
     * @param $driver
     * @param $config
     * @return bool|PDO
    */
    public function makePdo($driver, $config)
    {
        if (! Connection::has($driver)) {
            trigger_error("Unable connection driver '{$driver}' for PDO connection.");
        }

        return Connection::make($config);
    }




    /**
     * @param ConfigurationBag $config
     * @param string|null $database
     * @return string
    */
    protected function makeDSN(ConfigurationBag $config, string $database = null): string
    {
         $dsn =  sprintf('%s:host=%s;port=%s;',
             $config['driver'],
             $config['host'],
             $config['port']
         );

         if ($database) {
              return sprintf('%s;dbname=%s;', $dsn, $database);
         }

         return $dsn;
    }





    /**
     * @param ConfigurationBag $config
     * @param string|null $database
     * @return array
    */
    protected function getCredentials(ConfigurationBag $config, string $database = null): array
    {
        return [
            'dsn'      => $this->makeDSN($config, $database),
            'username' => $this->getUsername(),
            'password' => $this->getPassword(),
            'options'  => $config['options']
        ];
    }





    /**
     * @inheritDoc
    */
    public function transaction(Closure $closure): bool
    {
        try {

            $this->beginTransaction();

            $closure($this->createNativeQuery());

            $this->commit();

        } catch (PDOException $e) {

            $this->rollback();

            trigger_error($e->getMessage());
        }

        return true;
    }




    /**
     * @inheritDoc
    */
    public function reconnect($config)
    {
         if ($this->hasDatabase()) {
             $this->setConnection(
                 $this->makePdo($config['driver'], $this->getCredentials($config, $config['database']))
             );
         }
    }




    /**
     * @inheritDoc
     */
    public function createDatabase(): bool
    {
        return $this->unableMethodException(__METHOD__);
    }



    /**
     * @inheritDoc
     */
    public function dropDatabase()
    {
        $this->unableMethodException(__METHOD__);
    }




    /**
     * @inheritDoc
    */
    public function showDatabases()
    {
          $this->unableMethodException(__METHOD__);
    }



    /**
     * @inheritDoc
     */
    public function showTables()
    {
        $this->unableMethodException(__METHOD__);
    }



    /**
     * @inheritDoc
    */
    public function showInformationSchema()
    {
        $this->unableMethodException(__METHOD__);
    }


    
    
    /**
     * @return bool
    */
    public function hasDatabase(): bool
    {
         return in_array($this->getDatabase(), (array) $this->showDatabases());
    }



    /**
     * @param $method
     * @return bool
    */
    protected function unableMethodException($method): bool
    {
        return trigger_error("Unable method {$method} must be implements inside class : " . get_called_class());
    }
}