<?php
namespace Laventure\Component\Database;



use Laventure\Component\Database\Connection\Contract\QueryInterface;
use Laventure\Component\Database\Connection\Drivers\PDO\Contract\PdoConnectionInterface;
use Laventure\Component\Database\Manager\DatabaseManager;
use Laventure\Component\Database\Migration\Migrator;
use Laventure\Component\Database\ORM\Manager\Contract\EntityManagerInterface;
use Laventure\Component\Database\ORM\Manager\Contract\EntityManagerServiceInterface;
use Laventure\Component\Database\ORM\Manager\EntityManager;
use Laventure\Component\Database\Query\QueryBuilder;
use Laventure\Component\Database\Schema\Schema;
use PDO;


/**
 * @Manager
*/
class Manager extends DatabaseManager
{

      /**
       * @var Manager
      */
      protected static $instance;





      /**
       * @var EntityManager
      */
      protected $em;





      /**
       * @var bool
      */
      protected $booted = false;


      /**
       * @param array $credentials
       * @return void
      */
      public function addConnection(array $credentials)
      {
            if (! $this->booted()) {

                $this->connect($credentials['connection'], $credentials);

                $this->boot();
            }

      }




      /**
       * Boot application entity manager
       *
       * @param EntityManagerServiceInterface $service
       * @return void
      */
      public function bootEntityManager(EntityManagerServiceInterface $service)
      {
            $this->setEntityManager(new EntityManager($this->connection(), $service));
      }




      /**
       * Set own entity manager
       *
       * @param EntityManagerInterface $em
       * @return void
      */
      public function setEntityManager(EntityManagerInterface $em)
      {
           $this->em = $em;
      }





      /**
       * Get PDO connection
       *
       * @return PDO
      */
      public function pdo(): PDO
      {
           return $this->getConnection()->getPdo();
      }




      /**
        * Get PDO connection
        *
        * @return PdoConnectionInterface
      */
      public function getConnection(): PdoConnectionInterface
      {
           return $this->factory->getPdoConnection($this->getCurrentConnection());
      }




      /**
       * Get PDO connection by given name
       *
       * @param string|null $name
       * @return PdoConnectionInterface
      */
      public function pdoConnection(string $name): PdoConnectionInterface
      {
            return $this->factory->getPdoConnection($name);
      }




      /**
       * @return Manager
      */
      public static function getInstance(): Manager
      {
          if (! static::$instance) {
              trigger_error("Could not get instance of (". __CLASS__.") if you are not boot manager.");
          }

          return static::$instance;
     }



     /**
      * Get entity manager
      *
      * @return EntityManager
     */
     public function getEntityManager(): EntityManager
     {
          if (! $this->em) {
               trigger_error("Unable entity manager in class : ". __CLASS__);
          }

          return $this->em;
     }




     /**
      * Compact method for checking entity manager
      *
      * @return EntityManager
     */
     public function em(): EntityManager
     {
          return $this->getEntityManager();
     }




     /**
      * Create database
      *
      * @return mixed
     */
     public function createDatabase()
     {
          return $this->connection()->createDatabase();
     }



    /**
     * Drop database
     *
     * @return mixed
    */
    public function dropDatabase()
    {
        return $this->connection()->createDatabase();
    }





     /**
      * Create a new schema
      *
      * @return Schema
     */
     public function schema(): Schema
     {
          return new Schema($this->connection());
     }




     /**
      * Query a new query
      *
      * @param string $name
      * @return QueryBuilder
     */
     public function table(string $name): QueryBuilder
     {
          return new QueryBuilder($this->connection(), $name);
     }




     /**
      * Migrator
      *
      * @return Migrator
     */
     public function migration(): Migrator
     {
          return new Migrator($this->connection());
     }





     /**
      * Execute query
      *
      * @param $sql
      * @param array $params
      * @return QueryInterface
     */
     public function query($sql, array $params = []): QueryInterface
     {
          return $this->connection()->query($sql, $params);
     }




     /**
      * @param $sql
      * @return mixed
     */
     public function exec($sql)
     {
          return $this->connection()->exec($sql);
     }



     /**
      * @return mixed
     */
     public function lastId()
     {
          return $this->getConnection()->lastInsertId();
     }



     /**
      * @return bool
     */
     public function booted(): bool
     {
          return $this->booted;
     }



     /**
      * @return void
     */
     private function boot()
     {
         $this->booted   = true;
         self::$instance = $this;
     }


}