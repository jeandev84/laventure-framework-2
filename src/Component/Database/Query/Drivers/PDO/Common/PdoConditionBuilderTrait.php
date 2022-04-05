<?php
namespace Laventure\Component\Database\Query\Drivers\PDO\Common;


/**
 * @PdoConditionBuilderTrait
*/
trait PdoConditionBuilderTrait
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
                    $conditions[] = "{$key} $operator :{$key}";
                    $this->setParameter($key, $value);
                }
           }

           return parent::addConditions($conditions);
     }
}