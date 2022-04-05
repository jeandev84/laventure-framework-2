<?php
namespace Laventure\Component\Database\Query;



use Laventure\Component\Database\Connection\Contract\ConnectionInterface;
use Laventure\Component\Database\Connection\Drivers\PDO\Contract\PdoConnectionInterface;
use Laventure\Component\Database\Query\Builders\Common\SqlBuilder;
use Laventure\Component\Database\Query\Contract\SqlQueryBuilderInterface;
use Laventure\Component\Database\Query\Builders\DeleteBuilder;
use Laventure\Component\Database\Query\Builders\InsertBuilder;
use Laventure\Component\Database\Query\Builders\SelectBuilder;
use Laventure\Component\Database\Query\Builders\UpdateBuilder;


/**
 * @QueryBuilder
*/
class QueryBuilder
{


       /**
        * @var SqlQueryBuilderInterface
       */
       protected $builder;




       /**
        * @var string
       */
       protected $tableName;




       /**
        * @var string
       */
       protected $entityClass;


       /**
        * @var
       */
       protected $connection;




       /**
        * QueryBuilder constructor
        *
        * @param ConnectionInterface $connection
        * @param string $tableName
        * @param string|null $entityClass
       */
       public function __construct(ConnectionInterface $connection, string $tableName, string $entityClass = null)
       {
            $this->builder = QueryBuilderFactory::create($connection);

            $this->table($tableName);

            if ($entityClass) {
                 $this->with($entityClass);
            }

            $this->connection = $connection;
       }



       /**
        * @param string $tableName
        * @return void
       */
       public function table(string $tableName)
       {
            $this->tableName = $tableName;
       }





       /**
        * @param $entityClass
        * @return void
       */
       public function with($entityClass)
       {
            $this->entityClass = $entityClass;
       }




       /**
        * Select query
        *
        * @param array $selects
        * @param array $wheres
        * @return SelectBuilder
       */
      public function select(array $selects = ["*"], array $wheres = []): SelectBuilder
      {
           $builder = $this->builder->select($selects, $this->tableName);
           $builder->addConditions($this->prepareConditions($wheres));
           $builder->setEntityClass($this->entityClass);
           return $builder;
      }




      /**
       * Insert data to the table
       *
       * @param array $attributes
       * @return mixed
      */
      public function insert(array $attributes)
      {
            return $this->builder->insert($attributes, $this->tableName)
                                 ->execute();
      }



      /**
       * Update data from table
       *
       * @param array $attributes
       * @param array $wheres
       * @return UpdateBuilder
      */
      public function update(array $attributes, array $wheres = []): UpdateBuilder
      {
           $builder =  $this->builder->update($attributes, $this->tableName);
           $builder->addConditions($this->prepareConditions($wheres));
           return $builder;
      }




      /**
       * Delete query
       *
       * @param array $wheres
       * @return DeleteBuilder
      */
      public function delete(array $wheres = []): DeleteBuilder
      {
           $builder = $this->builder->delete($this->tableName);
           $builder->addConditions($this->prepareConditions($wheres));
           return $builder;
      }




      /**
       * @param array $wheres
       * @return array
      */
      protected function prepareConditions(array $wheres): array
      {
           $conditions = [];

           foreach ($wheres as $key => $value) {
               $conditions[] = [$key, "=", $value];
           }

           return $conditions;
      }




      /**
       * @param SqlBuilder $builder
       * @param array $wheres
       * @return void
       */
      /*
      private function addConditions(SqlBuilder $builder, array $wheres)
      {
           foreach ($wheres as $key => $value) {
                if ($this->connection instanceof PdoConnectionInterface) {
                      $builder->andWhere(":{$key} = :$key");
                      $builder->setParameter($key, $value);
                }else {
                    $builder->andWhere("{$key} = '$value'");
                }
           }
      }
      */
}