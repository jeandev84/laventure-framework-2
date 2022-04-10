<?php
namespace Laventure\Component\Database\Connection\Drivers\PDO\Connectors;


use Laventure\Component\Database\Connection\Configuration\ConfigurationBag;
use Laventure\Component\Database\Connection\Drivers\PDO\PdoConnection;



/**
 * @SqliteConnection
*/
class SqliteConnection extends PdoConnection
{
    /**
     * @return string
    */
    public function getName(): string
    {
        return 'sqlite';
    }


    /**
     * @param ConfigurationBag $config
     * @param string|null $database
     * @return string
    */
    protected function dsn(ConfigurationBag $config, string $database = null): string
    {
          return sprintf('%s:%s', $config['connection'], $config['database']);
    }





    /**
     * @return null
    */
    protected function getUsername()
    {
         return null;
    }



    protected function getPassword()
    {
        return null;
    }

}