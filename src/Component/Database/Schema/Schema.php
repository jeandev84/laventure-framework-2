<?php
namespace Laventure\Component\Database\Schema;



use Closure;
use Laventure\Component\Database\Connection\Contract\ConnectionInterface;
use Laventure\Component\Database\Schema\BluePrint\BluePrintFactory;

/**
 * @Schema
*/
class Schema
{

       /**
        * Database connection
        *
        * @var ConnectionInterface
       */
       protected $connection;



       /**
        * Schema constructor
        *
        * @param ConnectionInterface $connection
       */
       public function __construct(ConnectionInterface $connection)
       {
             $this->connection = $connection;
       }




       /**
        * Create a schema table
        *
        * @param string $table
        * @param Closure $closure
        * @return mixed
       */
       public function create(string $table, Closure $closure)
       {
            $bluePrint = $this->factory($table);

            $closure($bluePrint);

            return $bluePrint->createTable();
       }




       /**
        * Drop table
        *
        * @param $table
        * @return mixed
       */
       public function dropTable($table)
       {
           return $this->factory($table)->dropTable();
       }




       /**
        * Drop table if exist
        *
        * @param $table
        * @return mixed
       */
       public function dropIfExists($table)
       {
           return $this->factory($table)->dropTableIfExists();
       }




       /**
        * Truncate table
        *
        * @param $table
        * @return mixed
       */
       public function truncateTable($table)
       {
           return $this->factory($table)->truncateTable();
       }




       /**
        * Truncate cascade
        *
        * @param string $table
        * @return mixed
       */
       public function truncateCascade(string $table)
       {
           return $this->factory($table)->truncateTable();
       }




       /**
        * @param $table
        * @return mixed
       */
       public function showTableColumns($table)
       {
            return $this->factory($table)->showTableColumns();
       }




       /**
        * @param $table
        * @return mixed
       */
       public function describeTable($table)
       {
           return $this->factory($table)->describeTable();
       }




       /**
        * Show tables information
        *
        * @return mixed
       */
       public function showTables()
       {
           return $this->connection->showTables();
       }



       /**
        * Show schema information
        *
        * @return void
       */
       public function showInformation()
       {
            return $this->connection->showInformationSchema();
       }




       /**
         * Get connection
         *
         * @return ConnectionInterface
       */
       public function getConnection(): ConnectionInterface
       {
            return $this->connection;
       }



       /**
        * Blueprint factory
        *
        * @param string $table
        * @return bool|BluePrint\BluePrint
       */
       protected function factory(string $table)
       {
           return BluePrintFactory::create($this->connection, $table);
       }
}