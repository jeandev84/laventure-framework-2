<?php
namespace Laventure\Foundation\Loader;


use Laventure\Component\Console\Command\Command;
use Laventure\Component\Console\Console;
use Laventure\Component\Container\Container;
use Laventure\Component\FileSystem\FileSystem;
use Laventure\Foundation\Loader\Common\Loader;


/**
 * @CommandLoader
*/
class CommandLoader extends Loader
{

      /**
       * @var Console
      */
      protected $console;




      /**
       * @param Container $app
       * @param Console $console
      */
      public function __construct(Container $app, Console $console)
      {
            parent::__construct($app);
            $this->console = $console;
      }



      /**
       * Example: app/Console/Command/HelloCommand.php
       *  Make namespace App\Console\Command\HelloCommand
       *
       * @param FileSystem $fileSystem
       * @return void
      */
      public function loadCommands(FileSystem $fileSystem)
      {
          foreach ($this->getFileNames($fileSystem) as $commandClass) {
               $commandClass = $this->loadNamespace($commandClass);
               $this->resolveCommand($commandClass);
          }
      }



      /**
       * @param string[] $commands
       * @return void
      */
      public function loadResolvedCommands(array $commands)
      {
           foreach ($commands as $commandClass) {
               $this->resolveCommand($commandClass);
           }
      }



      /**
       * Example: App\Console\Command\FooCommand
       *
       * @param string $commandClass
       * @return void
      */
      public function resolveCommand(string $commandClass)
      {
          $command  = $this->app->get($commandClass);

          if ($command instanceof Command) {
              $this->console->addCommand($command);
          }
      }
}