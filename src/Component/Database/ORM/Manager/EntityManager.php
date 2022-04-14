<?php
namespace Laventure\Component\Database\ORM\Manager;



use Laventure\Component\Database\Connection\Contract\ConnectionInterface;
use Laventure\Component\Database\Connection\Contract\QueryInterface;
use Laventure\Component\Database\ORM\Manager\Contract\EntityManagerInterface;
use Laventure\Component\Database\ORM\Manager\Contract\EntityManagerServiceInterface;
use Laventure\Component\Database\ORM\Manager\Persistence\Persistence;
use Laventure\Component\Database\ORM\Mapper\DataMapper;
use Laventure\Component\Database\ORM\Repository\Contract\EntityRepositoryInterface;
use Laventure\Component\Database\Query\QueryBuilder;


/**
 * @EntityManager
*/
class EntityManager implements EntityManagerInterface
{


    /**
     * Connection
     *
     * @var ConnectionInterface
    */
    protected $connection;





    /**
     * @var string
    */
    protected $entityClass;






    /**
     * @var Persistence
    */
    protected $persistence;





    /**
     * @var EntityManagerServiceInterface
    */
    protected $service;




    /**
     * @var DataMapper
    */
    protected $dataMapper;




    /**
     * @var object[]
    */
    protected $updates = [];





    /**
     * EntityManager constructor
     *
     * @param ConnectionInterface|null $connection
    */
    public function __construct(ConnectionInterface $connection, EntityManagerServiceInterface $service)
    {
          $this->connection   = $connection;
          $this->service      = $service;
          $this->persistence  = new Persistence($this);
          $this->dataMapper   = new DataMapper();
    }




    /**
     * @inheritDoc
    */
    public function with($entityClass): self
    {
          $this->entityClass = $entityClass;

          return $this;
    }




    /**
     * @param string $identity
     * @return void
    */
    public function primaryKey(string $identity)
    {
          $this->persistence->primaryKey($identity);
    }





    /**
     * @inheritDoc
    */
    public function getEntityClass(): string
    {
         if (! $this->entityClass) {
              trigger_error("Unable entity class to map inside class : ". __CLASS__);
         }

         return $this->entityClass;
    }





    /**
     * @inheritDoc
    */
    public function beginTransaction()
    {
         return $this->connection->beginTransaction();
    }





    /**
     * @inheritDoc
    */
    public function commit()
    {
         return $this->connection->commit();
    }





    /**
     * @inheritDoc
    */
    public function lastInsertId(): int
    {
        return $this->connection->lastInsertId();
    }




    /**
     * @inheritDoc
    */
    public function rollback()
    {
         return $this->connection->rollback();
    }





    /**
     * @inheritDoc
    */
    public function exec($sql)
    {
        return $this->connection->exec($sql);
    }





    /**
     * @inheritDoc
    */
    public function getConnection()
    {
         return $this->connection->getConnection();
    }



    /**
     * @return string
    */
    public function getTableName(): string
    {
        return $this->service->createTableName($this->getEntityClass());
    }




    /**
     * @inheritDoc
    */
    public function createQueryBuilder(): QueryBuilder
    {
         return new QueryBuilder($this->connection, $this->getTableName(), $this->getEntityClass());
    }




    /**
     * @inheritDoc
    */
    public function createNativeQuery($sql, array $params = []): QueryInterface
    {
         $query = $this->connection->query($sql, $params);
         $query->with($this->getEntityClass());

         return $query;
    }



    /**
     * @inheritDoc
    */
    public function persist($object)
    {
         $this->persistence->insertions($object);
    }



    /**
     * @inheritDoc
    */
    public function remove($object)
    {
         $this->persistence->deletions($object);
    }




    /**
     * @inheritDoc
    */
    public function flush()
    {
        $this->preFlush();

        $this->persistence->flush();
    }



    /**
     * @return void
    */
    public function preFlush()
    {
         $this->updates($this->getCollectedObjects());
    }




    /**
     * @return object[]
    */
    public function getCollectedObjects(): array
    {
        return $this->connection->getCollection()
                                ->getObjects();
    }



    /**
     * @inheritDoc
    */
    public function transaction(callable $closure)
    {
         return $this->connection->transaction($closure);
    }




    /**
     * @param array $objects
     * @return void
    */
    public function updates(array $objects)
    {
         foreach ($objects as $object) {
              $this->persist($object);
         }

         $this->updates = $objects;
    }




    /**
     * @inheritDoc
    */
    public function getRepository($name): EntityRepositoryInterface
    {
         return $this->service->createRepository($name);
    }




    /**
     * @param array $attributes
     * @return mixed
    */
    public function insert(array $attributes)
    {
         $qb = $this->createQueryBuilder();

         return $qb->insert($attributes);
    }



    /**
     * @param array $attributes
     * @param array $wheres
     * @return void
    */
    public function update(array $attributes, array $wheres)
    {
         $qb = $this->createQueryBuilder();

         return $qb->update($attributes, $wheres)->execute();
    }



    /**
     * @param array $wheres
     * @return mixed
    */
    public function delete(array $wheres)
    {
         $qb = $this->createQueryBuilder();

         return $qb->delete($wheres)->execute();
    }



    /**
     * @param $object
     * @return array
    */
    public function getClassMetadata($object): array
    {
        return $this->dataMapper->map($object);
    }




    /**
     * @param $object
     * @return void
    */
    public function registerClass($object): self
    {
        if (is_object($object)) {

            $entityClass = get_class($object);

            $this->with($entityClass);
        }

        return $this;
    }
}