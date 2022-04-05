<?php
namespace Laventure\Component\Database\Migration\Contract;


/**
 * @MigratorInterface
*/
interface MigratorInterface
{


    /**
     * Returns table name version migrations
     *
     * @return mixed
    */
    public function getTableName();




    /**
     * Get migrations
     *
     * @return mixed
    */
    public function getMigrations();




    /**
     * Get old executed migrations
     *
     * @return mixed
    */
    public function getOldMigrations();





    /**
     * Create a migration table
     *
     * @return mixed
    */
    public function createMigrationTable();





    /**
     * Create all schema database
     *
     * @return mixed
    */
    public function migrate();




    /**
     * Truncate all schema tables
     *
     * @return mixed
    */
    public function rollback();





    /**
     * Remove all schema table
     *
     * @return mixed
    */
    public function reset();




    /**
     * Remove all schema table and all migration files
     *
     * @return mixed
    */
    public function clean();
}