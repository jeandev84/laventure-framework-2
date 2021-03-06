<?php
namespace Laventure\Component\Database\Connection\Drivers\PDO;


use Closure;
use Exception;
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
            $this->makePdo($config['connection'], $this->getCredentials($config))
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
         return  (function () {
             return $this->getPdo();
         })();
    }


    /**
     * @inheritDoc
    */
    public function beginTransaction()
    {
        return $this->getConnection()->beginTransaction();
    }


    /**
     * @inheritDoc
    */
    public function commit()
    {
        return $this->getConnection()->commit();
    }



    /**
     * @inheritDoc
    */
    public function rollback()
    {
        return $this->getConnection()->rollBack();
    }



    /**
     * @inheritDoc
    */
    public function lastInsertId(): int
    {
        return $this->getConnection()->lastInsertId();
    }



    /**
     * @return QueryInterface
    */
    public function createNativeQuery(): QueryInterface
    {
         return new Query($this->getConnection());
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
        return $this->getConnection()->exec($sql);
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
     * @throws Exception
    */
    public function getPdo(): PDO
    {
        if (! $this->connected()) {
            throw new Exception("unable PDO connection.");
        }

        return $this->connection;
    }




    /**
     * @param $connection
     * @param $config
     * @return bool|PDO
    */
    public function makePdo($connection, $config)
    {
        if (! Connection::has($connection)) {
            trigger_error("Unable connection '{$connection}' for PDO.");
        }

        return Connection::make($config);
    }




    /**
     * @param ConfigurationBag $config
     * @param string|null $database
     * @return string
    */
    protected function dsn(ConfigurationBag $config, string $database = null): string
    {
         $dsn =  sprintf('%s:host=%s;port=%s;', $config['connection'], $config['host'], $config['port']);

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
            'dsn'      => $this->dsn($config, $database),
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
                 $this->makePdo($config['connection'], $this->getCredentials($config, $config['database']))
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