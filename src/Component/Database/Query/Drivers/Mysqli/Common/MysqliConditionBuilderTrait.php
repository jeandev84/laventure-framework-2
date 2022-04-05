<?php
namespace Laventure\Component\Database\Query\Drivers\Mysqli\Common;


/**
 * @MysqliConditionBuilderTrait
*/
trait MysqliConditionBuilderTrait
{

     /**
      * @param array $arguments
      * @return $this
     */
     public function addConditions(array $arguments): self
     {
           $conditions = [];

           foreach ($arguments as $argument) {
                if (count($argument) === 3) {
                    list($key, $operator, $value) = $argument;
                    $conditions[] = "{$key} $operator '{$value}'";
                }
           }

           return parent::addConditions($conditions);
     }
}