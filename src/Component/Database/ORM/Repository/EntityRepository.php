<?php
namespace Laventure\Component\Database\ORM\Repository;


use Laventure\Component\Database\ORM\Manager\EntityManager;
use Laventure\Component\Database\ORM\Repository\Contract\EntityRepositoryInterface;
use Laventure\Component\Database\Query\Builders\SelectBuilder;
use Laventure\Component\Database\Query\QueryBuilder;


/**
 * @EntityRepository
*/
class EntityRepository implements EntityRepositoryInterface
{

       /**
        * @var EntityManager
       */
       protected $em;




       /**
        * EntityRepository constructor
        *
        * @param EntityManager $em
        * @param string $entityClass
       */
       public function __construct(EntityManager $em, string $entityClass)
       {
             $em->with($entityClass);
             $this->em = $em;
       }



       /**
        * Get table name
        *
        * @return string
       */
       protected function getTableName(): string
       {
            return $this->em->getTableName();
       }




       /**
        * @param $alias
        * @return SelectBuilder
       */
       public function createQueryBuilder($alias): SelectBuilder
       {
            return $this->em->createQueryBuilder()
                            ->select(['*'])
                            ->from($this->getTableName(), $alias);
       }





       /**
        * @param array $selects
        * @return QueryBuilder|SelectBuilder
       */
       public function createNativeQuery(array $selects = ['*'])
       {
            $qb =  $this->em->createQueryBuilder();

            if ($selects) {
                return $qb ->select($selects);
            }

            return  $qb;
       }



       /**
         * @inheritDoc
       */
       public function findOneBy(array $criteria)
       {
             return $this->selectCriteria($criteria)
                         ->getQuery()
                         ->getOneOrNullResult();
       }




       /**
         * @inheritDoc
       */
       public function findBy(array $criteria)
       {
           return $this->selectCriteria($criteria)
                       ->getQuery()
                       ->getResult();
       }




      /**
       * @inheritDoc
      */
      public function findAll()
      {
            return $this->em->createQueryBuilder()
                            ->select()
                            ->getQuery()
                            ->getResult();

      }




      /**
       * @param array $criteria
       * @return SelectBuilder
      */
      protected function selectCriteria(array $criteria): SelectBuilder
      {
            return $this->em->createQueryBuilder()
                            ->select(['*'], $criteria);
      }
}