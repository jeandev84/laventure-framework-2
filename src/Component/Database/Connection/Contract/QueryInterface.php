<?php
namespace Laventure\Component\Database\Connection\Contract;



/**
 * @QueryInterface
*/
interface QueryInterface extends QueryResultInterface
{

    /**
     * Prepare query
     *
     * @param $sql
     * @param array $params
     * @return mixed
    */
    public function prepare($sql, array $params = []);




    /**
     * Set entity Class
     *
     * @param $entityClass
     * @return mixed
    */
    public function with($entityClass);




    /**
     * Execute query
     *
     * @return mixed
    */
    public function execute();





    /**
     * Fetch mode
     *
     * @param int $fetchMode
     * @return mixed
    */
    public function fetchMode($fetchMode);




    /**
     * Get error info
     *
     * @return mixed
    */
    public function getErrorInfo();




    /**
     * Query log
     *
     * @return mixed
    */
    public function getQueryLogs();
}