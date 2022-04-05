<?php
namespace Laventure\Component\Database\ORM\Model;


use Laventure\Component\Database\Manager;
use Laventure\Component\Database\ORM\Model\Contract\ActiveRecordInterface;


/**
 * @ActiveRecord
*/
class ActiveRecord implements ActiveRecordInterface
{


    /**
     * Primary key
     *
     * @var string
    */
    protected $primaryKey = 'id';




    /**
     * Table name
     *
     * @var string
    */
    protected $table;





    /**
     * @return Manager
    */
    public static function getDB(): Manager
    {
        $db = Manager::getInstance();
        $db->setEntityManager(null);
        return $db;
    }




    /**
     * @inheritDoc
    */
    public function getPrimaryKey(): string
    {
         return $this->primaryKey;
    }




    /**
     * @inheritDoc
    */
    public function getTable()
    {
         return $this->table;
    }




    /**
     * @inheritDoc
    */
    public function findOne($id)
    {
        static::getDB()->table($this->getTable())
                       ->select(["*"], [$this->getPrimaryKey() => $id])
                       ->getQuery()
                       ->getOneOrNullResult();
    }



    /**
     * @inheritDoc
    */
    public function findAll()
    {
         static::getDB()->table($this->getTable())
                        ->select()
                        ->getQuery()
                        ->getResult();
    }



    /**
     * @inheritDoc
    */
    public function insert(array $attributes)
    {
         static::getDB()->table($this->getTable())
                        ->insert($attributes);
    }




    /**
     * @inheritDoc
    */
    public function update(array $attributes, $id)
    {
         return static::getDB()->table($this->getTable())
                        ->update($attributes, [$this->getPrimaryKey() => $id])
                        ->execute();
    }




    /**
     * @inheritDoc
    */
    public function delete($id)
    {
         return static::getDB()->table($this->getTable())
                       ->delete([$this->getPrimaryKey() => $id])
                       ->execute();
    }



    /**
     * @inheritDoc
    */
    public function lastId()
    {
         return static::getDB()->lastId();
    }
}