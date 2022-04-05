<?php
namespace Laventure\Component\Database\Connection;


use Laventure\Component\Database\Connection\Configuration\ConfigurationBag;
use Laventure\Component\Database\Connection\Contract\ResultCollectionInterface;


/**
 * @IConnection
*/
abstract class IConnection
{

      /**
       * @var ConfigurationBag
      */
      protected $config;




       /**
         * @var mixed
       */
       protected $connection;




       /**
        * Collect different results after execution queries
        *
        * @var ResultCollection
       */
       protected $collections;





       /**
        * Connection constructor.
        *
        * @param array $credentials
       */
       public function __construct(array $credentials = [])
       {
            if ($credentials) {
                 $this->connect($credentials);
            }

            $this->collections = new ResultCollection();
       }




       /**
        * @param $credentials
        * @return mixed
       */
       abstract public function connect($credentials);




       /**
        * @param array $credentials
        * @return ConfigurationBag
       */
       protected function parseCredentials(array $credentials): ConfigurationBag
       {
            return $this->config = new ConfigurationBag($credentials);
       }



       /**
        * Get username
        *
        * @return mixed
       */
       protected function getUsername()
       {
            return $this->config['username'];
       }




      /**
       * @return mixed
      */
      protected function getPassword()
      {
          return $this->config['password'];
      }





      /**
       * Example : prefix = laventure_ , table = users => tableName = laventure_users
       * @inheritDoc
      */
      public function getRealTableName(string $table): string
      {
           return $this->config['prefix'] . $table;
      }





      /**
       * @return mixed|null
      */
      public function getDatabase()
      {
          return $this->config['database'];
      }




      /**
       * @return mixed
      */
      public function getConnection()
      {
          return $this->connection;
      }




     /**
      * @param $connection
      * @return void
     */
     public function setConnection($connection)
     {
           $this->connection = $connection;
     }


     /**
      * @param $result
      * @return $this
     */
     public function collect($result): self
     {
          $this->collections->collect($result);

          return $this;
     }





     /**
      * @return ResultCollectionInterface
     */
     public function getCollection(): ResultCollectionInterface
     {
           return $this->collections;
     }





    /**
     * Determine if database
     * @return bool
    */
    public function createdDatabase(): bool
    {
        if ($this->createDatabase()) {
             if ($this->hasDatabase()) {
                 return true;
             }
        }

        return false;
    }
}