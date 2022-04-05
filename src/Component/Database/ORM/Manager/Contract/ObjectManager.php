<?php
namespace Laventure\Component\Database\ORM\Manager\Contract;


/**
 * @ObjectManager
*/
interface ObjectManager
{


    /**
     * @param $object
     * @return mixed
    */
    public function persist($object);




    /**
     * @param $object
     * @return mixed
    */
    public function remove($object);





    /**
     * @return mixed
    */
    public function flush();
}