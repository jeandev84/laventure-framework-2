<?php
namespace Laventure\Component\Database\Query;


use Laventure\Component\Database\Connection\Contract\ConnectionInterface;
use Laventure\Component\Database\Connection\Drivers\Mysqli\Contract\MysqliConnectionInterface;
use Laventure\Component\Database\Connection\Drivers\PDO\Contract\PdoConnectionInterface;
use Laventure\Component\Database\Query\Contract\SqlQueryBuilderInterface;
use Laventure\Component\Database\Query\Drivers\Mysqli\MysqliQueryBuilder;
use Laventure\Component\Database\Query\Drivers\PDO\MysqlQueryBuilder;
use Laventure\Component\Database\Query\Drivers\PDO\PgsqlQueryBuilder;


/**
 * @QueryBuilderFactory
*/
class QueryBuilderFactory
{

       /**
        * @return SqlQueryBuilderInterface
       */
       public static function create(ConnectionInterface $connection)
       {
            if ($connection instanceof PdoConnectionInterface) {
                    switch ($connection->getName()) {
                        case 'mysql':
                            return new MysqlQueryBuilder($connection);
                        case 'pgsql':
                            return new PgsqlQueryBuilder($connection);
                    }
            }


            if ($connection instanceof MysqliConnectionInterface) {
                 return new MysqliQueryBuilder($connection);
            }

            trigger_error("Could not make query builder for connection '{$connection->getName()}'");
      }
}