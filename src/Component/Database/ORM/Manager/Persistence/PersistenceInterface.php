<?php
namespace Laventure\Component\Database\ORM\Manager\Persistence;



/**
 * @PersistenceInterface
*/
interface PersistenceInterface
{


    /**
     * @return int
    */
    public function generateId(): int;




    /**
     * @param array $data
     * @return mixed
    */
    public function persist(array $data);




    /**
     * @param int $id
     * @return mixed
    */
    public function retrieve(int $id);




    /**
     * @param int $id
     * @return mixed
    */
    public function delete(int $id);
}