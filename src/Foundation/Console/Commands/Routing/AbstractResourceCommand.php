<?php
namespace Laventure\Foundation\Console\Commands\Routing;


use Laventure\Component\FileSystem\FileSystem;
use Laventure\Foundation\Application;
use Laventure\Foundation\Console\Commands\BaseCommand;
use Laventure\Foundation\Generators\ResourceGenerator;


/**
 * @AbstractResourceCommand
*/
abstract class AbstractResourceCommand extends BaseCommand
{

      /**
       * @var ResourceGenerator
      */
      protected $generator;


      /**
       * @param Application $app
       * @param FileSystem $fileSystem
       * @param ResourceGenerator $generator
       * @param string|null $name
      */
      public function __construct(
          Application $app,
          FileSystem $fileSystem,
          ResourceGenerator $generator,
          string $name = null
      )
      {
          parent::__construct($app, $fileSystem, $name);
          $this->generator = $generator;
      }
}