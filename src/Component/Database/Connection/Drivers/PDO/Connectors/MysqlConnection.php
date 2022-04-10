<?php
namespace Laventure\Component\Database\Connection\Drivers\PDO\Connectors;


use Laventure\Component\Database\Connection\Drivers\PDO\PdoConnection;



/**
 * @MysqlConnection
*/
class MysqlConnection extends PdoConnection
{


    /**
     * Get connection name
     *
     * @return string
    */
    public function getName(): string
    {
        return 'mysql';
    }


    /**
     * @return bool
    */
    public function createDatabase(): bool
    {
         /*
          todo implements this syntax
          Example
          CREATE DATABASE IF NOT EXISTS movies CHARACTER SET latin1 COLLATE latin1_swedish_ci
         */

         $sql= sprintf(
            'CREATE DATABASE IF NOT EXIST %s CHARACTER SET %s COLLATE %s;',
                   $this->config['database'],
                   $this->config->get('charset', 'utf8'),
                  $this->config->get('collation', 'utf8_general_ci')
         );

         return $this->exec($sql);
    }




    /**
     * Drop database
     *
     * @return bool
    */
    public function dropDatabase()
    {
         /*
          DROP SCHEMA IF EXISTS databaseName;
         */

        $sql = sprintf('DROP DATABASE IF EXISTS %s;', $this->config['database']);

        return $this->exec($sql);
    }
}