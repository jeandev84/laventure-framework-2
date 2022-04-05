<?php
namespace Laventure\Component\Database\Manager\Contract;


/**
 * @DatabaseConnectionInterface
*/
interface DatabaseConnectionInterface
{

    /**
     * Open connection
     *
     * @param array $credentials
     * @return mixed
    */
    public function open(array $credentials);




    /**
     * Get connection
     *
     * @return mixed
    */
    public function getConnection();




    /**
     * Close connection
     *
     * @return mixed
    */
    public function close();
}