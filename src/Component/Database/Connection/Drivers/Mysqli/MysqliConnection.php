<?php
namespace Laventure\Component\Database\Connection\Drivers\Mysqli;


use Closure;
use Laventure\Component\Database\Connection\Contract\QueryInterface;
use Laventure\Component\Database\Connection\Contract\ResultCollectionInterface;
use Laventure\Component\Database\Connection\Drivers\Mysqli\Contract\MysqliConnectionInterface;
use mysqli;




/**
 * @MysqliConnection
*/
class MysqliConnection  extends Connection implements MysqliConnectionInterface
{


    /**
     * @inheritDoc
    */
    public function getName(): string
    {
         return 'mysqli';
    }



    /**
     * @inheritDoc
    */
    public function connect($config)
    {

    }





    /**
     * @inheritDoc
    */
    public function connected(): bool
    {

    }




    /**
     * @inheritDoc
    */
    public function getConnection()
    {
        // TODO: Implement getConnection() method.
    }



    /**
     * @inheritDoc
    */
    public function beginTransaction()
    {
        // TODO: Implement beginTransaction() method.
    }



    /**
     * @inheritDoc
    */
    public function commit()
    {
        // TODO: Implement commit() method.
    }



    /**
     * @inheritDoc
    */
    public function rollback()
    {
        // TODO: Implement rollback() method.
    }



    /**
     * @inheritDoc
    */
    public function lastInsertId()
    {
        // TODO: Implement lastInsertId() method.
    }



     /**
      * @inheritDoc
     */
     public function query($sql, array $params = []): QueryInterface
     {

     }



     /**
      * @inheritDoc
     */
     public function exec($sql): bool
     {
        // TODO: Implement exec() method.
     }



    /**
     * @inheritDoc
    */
    public function disconnect()
    {
        // TODO: Implement disconnect() method.
    }

    /**
     * @inheritDoc
    */
    public function getMysqli(): mysqli
    {
        // TODO: Implement getMysqli() method.
    }



    public function createDatabase(): bool
    {
        // TODO: Implement createDatabase() method.
    }


    /**
     * @inheritDoc
    */
    public function dropDatabase()
    {
        // TODO: Implement dropDatabase() method.
    }




    /**
     * @inheritDoc
    */
    public function getRealTableName(string $table): string
    {
        // TODO: Implement getRealTableName() method.
    }

    /**
     * @inheritDoc
     */
    public function transaction(Closure $closure): bool
    {
        // TODO: Implement transaction() method.
    }

    /**
     * @inheritDoc
     */
    public function createNativeQuery(): QueryInterface
    {
        // TODO: Implement createNativeQuery() method.
    }


    /**
     * @inheritDoc
     */
    public function reconnect($config)
    {
        // TODO: Implement reconnect() method.
    }

    /**
     * @inheritDoc
     */
    public function showDatabases()
    {
        // TODO: Implement showDatabases() method.
    }

    /**
     * @inheritDoc
     */
    public function hasDatabase(): bool
    {
        // TODO: Implement hasDatabase() method.
    }

    /**
     * @inheritDoc
     */
    public function createdDatabase(): bool
    {
        // TODO: Implement createdDatabase() method.
    }

    /**
     * @inheritDoc
     */
    public function showTables()
    {
        // TODO: Implement showTables() method.
    }

    /**
     * @inheritDoc
     */
    public function showInformationSchema()
    {
        // TODO: Implement showInformationSchema() method.
    }

    /**
     * @inheritDoc
     */
    public function collect(array $results)
    {
        // TODO: Implement collectResults() method.
    }

    /**
     * @inheritDoc
     */
    public function getCollection(): ResultCollectionInterface
    {
        // TODO: Implement getResultCollection() method.
    }
}