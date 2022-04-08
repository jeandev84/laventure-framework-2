<?php
namespace Laventure\Foundation\Console\Commands\Database\Migration\Common;


use Laventure\Component\Database\Migration\Migrator;
use Laventure\Component\FileSystem\FileSystem;
use Laventure\Foundation\Application;
use Laventure\Foundation\Console\Commands\BaseCommand;
use Laventure\Foundation\Generator\MigrationGenerator;


/**
 * @AbstractMigrationCommand
*/
abstract class AbstractMigrationCommand extends BaseCommand
{


      /**
       * @var Migrator
      */
      protected $migrator;




      /**
       * @var MigrationGenerator
      */
      protected $generator;


      /**
       * @param Application $app
       * @param FileSystem $fileSystem
       * @param Migrator $migrator
       * @param MigrationGenerator $generator
       * @param string|null $name
      */
      public function __construct(
          Application $app,
          FileSystem $fileSystem,
          Migrator $migrator,
          MigrationGenerator $generator,
          string $name = null
      )
      {
           parent::__construct($app, $fileSystem, $name);
           $this->migrator = $migrator;
           $this->generator = $generator;
      }

}