<?php
namespace Laventure\Component\Database\Migration\Contract;


/**
 * @MigrationCollectionInterface
*/
interface MigrationCollectionInterface
{

       /**
        * Get all migrations
        *
        * @return mixed
       */
       public function getMigrations();




       /**
        * Get migration by given name
        *
        * @param $name
        * @return mixed
       */
       public function getMigration($name);





       /**
        * Remove migration by given name
        *
        * @param $name
        * @return mixed
       */
       public function removeMigration($name);





       /**
        * Remove all migrations
        *
        * @return mixed
       */
       public function removeMigrations();




       /**
        * Get new migrations
        *
        * @return mixed
       */
       public function getNewMigrations();




       /**
        * Get old or previous migrations
        *
        * @return mixed
       */
       public function getOldMigrations();




       /**
        * Remove all migration collections and files
        *
        * @return void
       */
       public function clearMigrations();
}