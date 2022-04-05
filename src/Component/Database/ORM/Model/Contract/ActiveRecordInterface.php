<?php
namespace Laventure\Component\Database\ORM\Model\Contract;


/**
 * @ActiveRecordInterface
*/
interface ActiveRecordInterface
{


    /**
     * Get primary key
     *
     * @return mixed
    */
    public function getPrimaryKey();





    /**
     * Get table name
     *
     * @return mixed
    */
    public function getTable();




    /**
     * Get last inserted id
     *
     * @return mixed
    */
    public function lastId();




    /**
     * Find one by primary key
     *
     * @param $id
     * @return mixed
    */
    public function findOne($id);




    /**
     * get all results
     *
     * @return mixed
    */
    public function findAll();




    /**
     * Insert attribute
     *
     * @param array $attributes
     * @return mixed
    */
    public function insert(array $attributes);




    /**
     * Update attributes by given primary key
     *
     * @param array $attributes
     * @param $id
     * @return mixed
    */
    public function update(array $attributes, $id);





    /**
     * Delete record by primary key
     *
     * @param $id
     * @return mixed
    */
    public function delete($id);
}