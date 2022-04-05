<?php
namespace Laventure\Component\Database\ORM\Manager\Persistence;


use Laventure\Component\Database\ORM\Manager\EntityManager;



/**
 * @Persistence
*/
class Persistence implements PersistenceInterface
{


     /**
      * @var EntityManager
     */
     protected $em;




     /**
      * Primary key
      *
      * @var string
     */
     protected $identity = 'id';



     /**
      * @var array
     */
     protected $updates = [];




     /**
      * @var array
     */
     protected $insertions = [];




     /**
      * @var array
     */
     protected $deletions = [];




     /**
      * @var array
     */
     protected $persists = [];




     /**
      * Persistence constructor.
      *
      * @param EntityManager $em
     */
     public function __construct(EntityManager $em)
     {
           $this->em = $em;
     }



     /**
      * @param $identity
      * @return void
     */
     public function primaryKey($identity)
     {
          $this->identity = $identity;
     }




     /**
      * Add objects to insert to the table
      *
      * @param $object
      * @return void
     */
     public function insertions($object)
     {
        if ($this->validate($object)) {
            $this->persists[] = $object;
        }
    }




    /**
     * Add object to updates
     *
     * @param $object
     * @return void
    */
    public function updates($object)
    {
        $this->updates[$object->getId()] = $object;
    }




    /**
     * @param $object
     * @return void
    */
    public function deletions($object)
    {
        if ($this->validate($object)) {
            $this->deletions[$object->getId()] = $object;
        }
    }



     /**
      * @inheritDoc
     */
     public function generateId(): int
     {
         return $this->em->lastInsertId();
     }




     /**
      * @inheritDoc
     */
     public function persist(array $data)
     {
         $attributes = $this->getInsertableAttributes($data);

         if (! $this->hasID($data)) {
              trigger_error("Undefined primary key '{$this->identity}' for persistence data.");
         }

         if (! empty($data[$this->identity])) {
             $this->em->update($attributes, [$this->identity => $data[$this->identity]]);
         } else {
             $this->em->insert($attributes);
         }
     }




     /**
      * @inheritDoc
     */
     public function retrieve(int $id)
     {
         $qb = $this->em->createQueryBuilder();

         return $qb->select(["*"], [$this->identity => $id])
                   ->from($this->em->getTableName())
                   ->getQuery()
                   ->getOneOrNullResult();
     }




     /**
      * @inheritDoc
     */
     public function delete(int $id)
     {
          return $this->em->delete([$this->identity => $id]);
     }




     /**
      * @return void
     */
     public function flush()
     {
          $this->em->transaction(function () {

              if ($this->persists) {
                  $this->save($this->persists);
              }

              if ($this->deletions) {
                  $this->remove($this->deletions);
              }
          });
     }



     /**
      * Save all persists objects
      *
      * @param object[] $objects
      * @return void
     */
     public function save(array $objects)
     {
          foreach ($objects as $object) {

               $attributes = $this->getAttributes($object);

               $this->persist($attributes);

               if ($id = $this->getID($attributes)) {
                    $this->updates[$id] = $object;
               }else {
                    $this->insertions[$this->generateId()] = $object;
               }
          }
     }



     /**
      * Remove all deletions object
      *
      * @param object[] $objects
      * @return void
     */
     public function remove(array $objects)
     {
         foreach ($objects as $object) {
              $attributes = $this->getAttributes($object);
              $this->delete($this->getID($attributes));
         }
     }




     /**
      * Get attributes to insert
      *
      * @param array $attributes
      * @return array
     */
     protected function getInsertableAttributes(array $attributes): array
     {
           unset($attributes[$this->identity]);

           return $attributes;
     }



     /**
      * Determine if the given object has ID
      *
      * @param array $attributes
      * @return bool
     */
     protected function hasID(array $attributes): bool
     {
          return array_key_exists($this->identity, $attributes);
     }




     /**
      * Get object value object ID
      *
      * @param array $attributes
      * @return int
     */
     protected function getID(array $attributes): int
     {
          return  $this->hasID($attributes) ? (int) $attributes[$this->identity] : 0;
     }



     /**
      * Get all attributes of object
      *
      * @param $object
      * @return array
     */
     protected function getAttributes($object): array
     {
         return $this->em->getClassMetadata($object);
     }



    /**
     * @param $object
     * @return bool
     */
    protected function isObject($object): bool
    {
        return is_object($object);
    }




    /**
     * @param $object
     * @return mixed
     */
    protected function validate($object)
    {
        if($this->isObject($object)) {

            if (! method_exists($object, 'getId')) {
                trigger_error("Method getId() must be implemented inside class ". get_class($object));
            }

            $this->em->registerClass($object);

            return $object;
        }

        return false;
    }
}