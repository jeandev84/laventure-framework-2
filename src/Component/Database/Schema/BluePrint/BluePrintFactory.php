<?php
namespace Laventure\Component\Database\Schema\BluePrint;



use Laventure\Component\Database\Connection\Contract\ConnectionInterface;
use Laventure\Component\Database\Connection\Drivers\PDO\Connectors\MysqlConnection;
use Laventure\Component\Database\Connection\Drivers\PDO\Connectors\PgsqlConnection;
use Laventure\Component\Database\Schema\BluePrint\Drivers\MysqlBluePrint;
use Laventure\Component\Database\Schema\BluePrint\Drivers\PgsqlBluePrint;


/**
 * @BluePrintFactory
*/
class BluePrintFactory
{

      /**
       * Create a blueprint from factory
       *
       * @param ConnectionInterface $connection
       * @param string $table
       * @return bool|BluePrint
      */
      public static function create(ConnectionInterface $connection, string $table): BluePrint
      {
           if ($connection instanceof MysqlConnection) {
                return new MysqlBluePrint($connection, $table);
           }

           if ($connection instanceof PgsqlConnection) {
                return new PgsqlBluePrint($connection, $table);
           }

           return trigger_error("Could not create blue print instance for connection '{$connection->getName()}'");
      }
}