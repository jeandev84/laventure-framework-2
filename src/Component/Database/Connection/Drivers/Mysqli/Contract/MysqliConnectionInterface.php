<?php
namespace Laventure\Component\Database\Connection\Drivers\Mysqli\Contract;


use Laventure\Component\Database\Connection\Contract\ConnectionInterface;
use mysqli;


/**
 * @MysqliConnectionInterface
*/
interface MysqliConnectionInterface extends ConnectionInterface
{

      /**
       * @return mysqli
      */
      public function getMysqli(): mysqli;
}