<?php
namespace Laventure\Micro;


use Laventure\Component\Container\Container;


/**
 * @Container
*/
class DI extends Container
{

     /**
      * @var array
     */
     protected $definition;


     /**
      * @param array $definition
     */
     public function __construct(array $definition = [])
     {
          $this->definition = $definition;
     }
}