<?php
namespace Laventure\Component\Database\Query\Contract;



/**
 * @SqlQueryBuilderInterface
*/
interface SqlQueryBuilderInterface
{


    /**
     * @param array $selects
     * @param string $table
     * @return mixed
    */
    public function select(array $selects, string $table);




    /**
     * @param array $attributes
     * @param string $table
     * @return mixed
    */
    public function insert(array $attributes, string $table);




    /**
     * @param array $attributes
     * @param string $table
     * @return mixed
    */
    public function update(array $attributes, string $table);




    /**
     * @param $table
     * @return mixed
    */
    public function delete($table);
}