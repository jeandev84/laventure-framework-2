<?php
namespace Laventure\Component\Database\ORM\Manager\Contract;


use Laventure\Component\Database\Connection\Contract\QueryInterface;
use Laventure\Component\Database\Query\QueryBuilder;


/**
 * @EntityManagerInterface
*/
interface EntityManagerInterface extends ObjectManager
{


    /**
     * Begin transaction
     *
     * @return mixed
    */
    public function beginTransaction();




    /**
     * Commit query
     *
     * @return mixed
    */
    public function commit();



    /**
     * Get the last insert id
     *
     * @return mixed
    */
    public function lastInsertId();



    /**
     * Rollback query
     *
     * @return mixed
    */
    public function rollback();




    /**
     * Execute query
     *
     * @param $sql
     * @return mixed
    */
    public function exec($sql);




    /**
     * Get connection PDO, mysqli ...
     *
     * @return mixed
    */
    public function getConnection();



    /**
     * Get repository
     *
     * @param $name
     * @return mixed
    */
    public function getRepository($name);




    /**
     * Create a query builder
     *
     * @return QueryBuilder
    */
    public function createQueryBuilder(): QueryBuilder;


    /**
     * Create a native query
     *
     * @param $sql
     * @param array $params
     * @return QueryInterface
    */
    public function createNativeQuery($sql, array $params = []): QueryInterface;





    /**
     * Transaction several queries
     *
     * @param callable $closure
     * @return mixed
    */
    public function transaction(callable $closure);




    /**
     * Set entity class to manage
     *
     * @param $entityClass
     * @return mixed
    */
    public function with($entityClass);




    /**
     * Get entity class
     *
     * @return mixed
    */
    public function getEntityClass();

}