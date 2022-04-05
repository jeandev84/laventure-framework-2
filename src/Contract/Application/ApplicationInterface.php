<?php
namespace Laventure\Contract\Application;



/**
 * @ApplicationInterface
*/
interface ApplicationInterface
{


      /**
       * name of application
       *
       * @return string
      */
      public function getName(): string;






      /**
       * version of application
       *
       * @return string
      */
      public function getVersion(): string;

}