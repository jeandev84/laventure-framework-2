<?php
namespace Laventure\Component\Database\Connection\Contract;



/**
 * @QueryResultInterface
*/
interface QueryResultInterface
{


    /**
     * get all items
     *
     * @return mixed
    */
    public function getResult();



    /**
     * get one or null item
     *
     * @return mixed
    */
    public function getOneOrNullResult();



    /**
     * get array columns
     *
     * @return mixed
    */
    public function getArrayColumns();



    /**
     * get first result
     *
     * @return mixed
    */
    public function getFirstResult();




    /**
     * Get single scalar result
     *  may be to rename count()
     *
     * @return mixed
    */
    public function getSingleScalarResult();
}