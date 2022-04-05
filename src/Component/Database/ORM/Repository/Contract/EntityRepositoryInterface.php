<?php
namespace Laventure\Component\Database\ORM\Repository\Contract;



/**
 * @EntityRepositoryInterface
*/
interface EntityRepositoryInterface
{


    /**
     * Create a query builder
     *
     * @param $alias
     * @return void
    */
    public function createQueryBuilder($alias);




    /**
     * Find one query by criteria
     *
     * @param array $criteria
     * @return mixed
    */
    public function findOneBy(array $criteria);





    /**
     * Find all queries by criteria
     *
     * @param array $criteria
     * @return mixed
    */
    public function findBy(array $criteria);





    /**
     * Find all results
     *
     * @return mixed
    */
    public function findAll();
}