<?php
namespace Laventure\Component\Database\ORM\Model\Common;


use Laventure\Component\Database\Connection\Drivers\PDO\Contract\PdoConnectionInterface;
use Laventure\Component\Database\ORM\Model\ActiveRecord;
use Laventure\Component\Database\Query\Builders\Common\SqlBuilder;
use Laventure\Component\Database\Query\Builders\SelectBuilder;


/**
 * @AbstractModel
*/
abstract class AbstractModel extends ActiveRecord  implements \ArrayAccess
{

     use ModelTrait;




     /**
      * @var array
     */
     protected $selects = ["*"];





     /**
      * Model conditions
      *
      * @var array
     */
     protected $wheres = [];




     /**
      * Order by
      *
      * @var array
     */
     protected $orderBy = [];





     /**
      * @var array
     */
     protected $limit = [];




     /**
      * Method can call statically
      *
      * @var array
     */
     private static $callableStatic = [
         'select', 'where', 'create', 'all', 'delete'
     ];





     /**
      * @param $field
      * @param $value
     */
     public function __set($field, $value)
     {
         $this->setAttribute($field, $value);
     }



     /**
      * @param $field
      * @return mixed
     */
     public function __get($field)
     {
          return $this->getAttribute($field);
     }



     /**
      * @param mixed $offset
      * @return bool
     */
     public function offsetExists($offset): bool
     {
         return $this->hasAttribute($offset);
     }



     /**
      * @param mixed $offset
      * @return mixed|void
     */
     public function offsetGet($offset)
     {
         return $this->getAttribute($offset);
     }



     /**
      * @param mixed $offset
      * @param mixed $value
     */
     public function offsetSet($offset, $value)
     {
         $this->setAttribute($offset, $value);
     }



     /**
      * @param mixed $offset
     */
     public function offsetUnset($offset)
     {
         $this->removeAttribute($offset);
     }




     /**
      * @param array $selects
      * @return $this
     */
     public function select(array $selects = []): self
     {
           $this->selects = $selects;

           return $this;
     }





     /**
      * Set query conditions
      *
      * @param string $column
      * @param $value
      * @param string $operator
      * @return $this
     */
     public function where(string $column, $value, string $operator = '='): self
     {
           $this->wheres[] = [$column, $operator, $value];

           return $this;
     }




     /**
      * @param string $column
      * @param string $direction
      * @return $this
     */
     public function orderBy(string $column, string $direction = 'asc'): AbstractModel
     {
          $this->orderBy[] = compact('column', 'direction');

          return $this;
     }




     /**
      * @param int $limit
      * @param int $offset
      * @return $this
     */
     public function limit(int $limit, int $offset = 0): self
     {
           $this->limit = compact('limit', 'offset');

           return $this;
     }






     /**
      * @return mixed
     */
     public function get()
     {
           $qb = $this->selectQuery($this->selects);

           if ($this->wheres) {
              $qb->addConditions($this->wheres);
           }

          return $qb->getQuery()->getResult();
     }





     /**
      * @return void
     */
     public function one()
     {
         $qb = $this->selectQuery($this->selects);

         if ($this->wheres) {
             $qb->addConditions($this->wheres);
         }

         return $qb->getQuery()->getOneOrNullResult();
     }




     /**
      * @param array $attributes
      * @return void
     */
     public function create(array $attributes)
     {
           return $this->insert($attributes);
     }



     /**
      * @return mixed|void
     */
     public function all()
     {
         return parent::findAll();
     }



     /**
      * @param $id
      * @return mixed
     */
     public function delete($id = null)
     {
         if ($id) {
             return parent::delete($id);
         }

         if ($this->wheres) {
             return self::getDB()->table($this->getTable())
                                 ->delete()
                                 ->addConditions($this->wheres)
                                 ->execute();
         }

         return false;
     }




     public function save()
     {
         $columns    = $this->getTableColumns();
         $attributes =  $this->populateColumns($columns);

         if (! empty($this->guarded)) {
             $attributes = $this->guarded($attributes);
         }

         $id = $this->getPrimaryKeyValue();

         if ($id) {
             $this->update($attributes, $id);
         }else{
             $this->insert($attributes);
             $this->setAttribute($this->getPrimaryKey(), $this->lastId());
         }
     }



     /**
      * @param $name
      * @param $arguments
      * @return false|mixed|void
     */
     public static function __callStatic($name, $arguments)
     {
          if (! in_array($name, self::$callableStatic)) {
                trigger_error("Method '{$name}' can be called statically.");
          }

          return call_user_func_array($name, $arguments);
     }



    /**
     * Create a select query
     *
     * @param array $selects
     * @return SelectBuilder
    */
    protected function selectQuery(array $selects = []): SelectBuilder
    {
        $queryBuilder = static::getDB()->table($this->getTable());

        return $queryBuilder->select($selects);
    }


    /**
     * @param array $columns
     * @return array
    */
    private function populateColumns(array $columns): array
    {
        $attributes = [];

        foreach ($columns as $column) {

            if (! empty($this->insertable)) {
                if (\in_array($column, $this->insertable)) {
                    $attributes[$column] = $this->{$column};
                }
            }else {
                $attributes[$column] =  $this->{$column};
            }
        }

        return $attributes;
    }




    /**
     * @return mixed
    */
    private function getTableColumns()
    {
        return static::getDB()->schema()
                       ->showTableColumns($this->getTable());
    }



    /**
     * @param array $attributes
     * @return array
    */
    private function guarded(array $attributes): array
    {
        foreach ($this->guarded as $guarded) {
            if (isset($attributes[$guarded])) {
                unset($attributes[$guarded]);
            }
        }

        return $attributes;
    }



    /**
     * @return int
    */
    private function getPrimaryKeyValue(): int
    {
        return (int) $this->getAttribute($this->getPrimaryKey());
    }
}