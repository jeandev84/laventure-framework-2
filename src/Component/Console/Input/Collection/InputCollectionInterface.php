<?php
namespace Laventure\Component\Console\Input\Collection;


/**
 * @InputCollectionInterface
*/
interface InputCollectionInterface
{

      /**
       * Get arguments
       *
       * @return mixed
      */
      public function getArguments();




      /**
       * Get options
       *
       *
       * @return mixed
      */
      public function getOptions();
}