<?php
namespace Laventure\Component\Database\Query;


use Laventure\Component\Database\Connection\Contract\ConnectionInterface;
use Laventure\Component\Database\Connection\Contract\QueryInterface;


/**
 * @Query
*/
class Query
{


     /**
      * @var QueryInterface
     */
     protected $query;




     /**
      * @var ConnectionInterface
     */
     protected $connection;




     /**
      * @param QueryInterface $query
      * @param ConnectionInterface $connection
     */
     public function __construct(QueryInterface $query, ConnectionInterface $connection)
     {
           $this->query      = $query;
           $this->connection = $connection;
     }




     /**
      * Execute Query
      *
      * @return mixed
     */
     public function execute()
     {
         return $this->query->execute();
     }



     /**
      * Get results
      *
      * @return mixed
      */
     public function getResult(): array
     {
         return $this->collects($this->query->getResult());
     }




     /**
      * @return mixed
     */
     public function getOneOrNullResult()
     {
         return $this->collect($this->query->getOneOrNullResult());
     }




     /**
      * Get first result
      *
      * @return mixed
      */
     public function getFirstResult()
     {
         return $this->collect($this->query->getFirstResult());
     }


      /**
       * @return mixed
     */
      public function getArrayColumns()
      {
           return $this->query->getArrayColumns();
      }



      /**
       * @return mixed
      */
      public function getSingleScalarResult()
      {
          return $this->query->getSingleScalarResult();
      }




      /**
       * Get error info
       */
      public function getErrorInfo()
      {
          return $this->query->getErrorInfo();
      }




      /**
       * @param $result
       * @return mixed
      */
      protected function collect($result)
      {
           $this->connection->collect($result);

           return $result;
     }




     /**
      * @param array $results
      * @return array
     */
     protected function collects(array $results): array
     {
         foreach ($results as $result) {
             $this->collect($result);
         }

         return $results;
     }
}