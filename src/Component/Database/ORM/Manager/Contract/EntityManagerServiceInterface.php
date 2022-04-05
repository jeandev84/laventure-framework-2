<?php
namespace Laventure\Component\Database\ORM\Manager\Contract;



use Laventure\Component\Database\ORM\Repository\Contract\EntityRepositoryInterface;

/**
 * @EntityManagerServiceInterface
*/
interface EntityManagerServiceInterface
{

    /**
     * Create entity repository object
     *
     * @param string $entityClass
     * @return EntityRepositoryInterface
    */
    public function createRepository(string $entityClass): EntityRepositoryInterface;



    /**
     * Create the name of table
     *
     * @param string $entityClass
     * @return string
    */
    public function createTableName(string $entityClass): string;




    /**
     * Get entity class name from object
     *
     * @param object $object
     * @return string
    */
    public function getEntityClassName($object): string;
}