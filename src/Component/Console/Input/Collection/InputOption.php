<?php
namespace Laventure\Component\Console\Input\Collection;


use Laventure\Component\Console\Input\Collection\Parameter\InputParameter;


/**
 * @InputOption
*/
class InputOption extends InputParameter
{

     const NONE = 4;


     /**
      * @return bool
     */
     public function isNone(): bool
     {
         return $this->mode === self::NONE;
     }
}