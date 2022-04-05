<?php
namespace Laventure\Component\Database\Connection\Drivers\PDO\Contract;


use Laventure\Component\Database\Connection\Contract\ConnectionInterface;
use PDO;


/**
 * @PdoConnectionInterface
*/
interface PdoConnectionInterface extends ConnectionInterface
{
      public function getPdo(): PDO;
}