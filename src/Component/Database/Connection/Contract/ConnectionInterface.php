<?php
namespace Laventure\Component\Database\Connection\Contract;


use Closure;

/**
 * @ConnectionInterface
*/
interface ConnectionInterface
{

    /**
     * Connection name
     *
     * @return string
    */
    public function getName(): string;



    /**
     * Connect to database
     *
     * @param $config
     * @return mixed
    */
    public function connect($config);




    /**
     * Determine if the connection is established
     *
     * @return bool
    */
    public function connected(): bool;




    /**
     * Get connection
     *
     * @return mixed
    */
    public function getConnection();




    /**
     * @param $sql
     * @param array $params
     * @return QueryInterface
    */
    public function query($sql, array $params = []): QueryInterface;




    /**
     * Begin transaction
     *
     * @return mixed
    */
    public function beginTransaction();




    /**
     * Commit transaction
     *
     * @return mixed
    */
    public function commit();




    /**
     * Rollback transaction
     *
     * @return mixed
    */
    public function rollback();




    /**
     * Get a last insert ID
     *
     * @return mixed
    */
    public function lastInsertId();




    /**
     * Execute query
     *
     * @param $sql
     * @return mixed
    */
    public function exec($sql): bool;




    /**
     * Get real table with prefix
     *
     * @param string $table
     * @return string
    */
    public function getRealTableName(string $table): string;





    /**
     * Create a native query
     *
     * @return QueryInterface
    */
    public function createNativeQuery(): QueryInterface;




    /**
     * Execute queries
     *
     * @param Closure $closure
     * @return mixed
    */
    public function transaction(Closure $closure): bool;






    /**
     * Disconnect to database
     *
     * @return mixed
    */
    public function disconnect();







    /**
     * Reconnect to database
     *
     * @return mixed
    */
    public function reconnect($config);




    /**
     * Create database
     *
     * @return mixed
    */
    public function createDatabase(): bool;




    /**
     * Drop database
     *
     * @return mixed
    */
    public function dropDatabase();




    /**
     * Show database
     *
     * @return mixed
    */
    public function showDatabases();



    /**
     * Show tables
     *
     * @return mixed
    */
    public function showTables();





    /**
     * Show Schema information
     *
     * @return mixed
    */
    public function showInformationSchema();





    /**
     * Determine if database exist
     *
     * @return bool
    */
    public function hasDatabase(): bool;






    /**
     * Determine if database
     * @return bool
    */
    public function createdDatabase(): bool;




    /**
     * Collect mixed
     *
     * @return mixed
    */
    public function collect($result);





    /**
     * Get result collections
     *
     * @return ResultCollectionInterface
    */
    public function getCollection(): ResultCollectionInterface;
}