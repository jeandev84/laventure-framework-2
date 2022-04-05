<?php
namespace Laventure\Component\Database\Schema\BluePrint\Drivers\Contract;


use Laventure\Component\Database\Connection\Contract\ConnectionInterface;

/**
 * @BluePrintInterface
*/
interface BluePrintInterface
{

    /**
     * Set table
     *
     * @param string $table
     * @return mixed
    */
    public function with(string $table);





    /**
     * Get blueprint table
     *
     * @return mixed
    */
    public function getTable();






    /**
     * Create a schema table
     *
     * @return mixed
    */
    public function createTable();





    /**
     * Drop table
     *
     * @return mixed
    */
    public function dropTable();




    /**
     * Drop table if exists
     *
     * @return mixed
    */
    public function dropTableIfExists();




    /**
     * Truncate table
     *
     * @return mixed
    */
    public function truncateTable();




    /**
     * Truncate cascade
     *
     * @return mixed
    */
    public function truncateCascade();




    /**
     * Show columns table
     *
     * @return mixed
    */
    public function showTableColumns();




    /**
     * Describe table
     *
     * @return mixed
    */
    public function describeTable();




    /**
     * Get connection
     *
     * @return ConnectionInterface
    */
    public function getConnection(): ConnectionInterface;





    /**
     * Get columns
     *
     * @return mixed
    */
    public function getColumns();
}