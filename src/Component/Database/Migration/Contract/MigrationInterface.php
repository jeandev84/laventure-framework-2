<?php
namespace Laventure\Component\Database\Migration\Contract;


/**
 * @MigrationInterface
*/
interface MigrationInterface
{


     /**
      * Get migration name
      *
      * @return mixed
     */
     public function getName();




     /**
       * Get migration path
       *
       * @return mixed
     */
     public function getPath();



     /**
      * Create or Update database schema
      *
      * @return mixed
     */
     public function up();




     /**
      * Drop database schema
      *
      * @return mixed
     */
     public function down();
}