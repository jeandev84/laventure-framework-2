<?php
namespace Laventure\Foundation\Console;


use Laventure\Component\Console\Console;
use Laventure\Component\Console\Input\InputInterface;
use Laventure\Component\Console\Output\OutputInterface;
use Laventure\Contract\Console\Kernel as ConsoleKernelContract;
use Laventure\Foundation\Application;
use Laventure\Foundation\Console\Commands\Command\MakeCommand;
use Laventure\Foundation\Console\Commands\Database\Migration\MigrationInstallCommand;
use Laventure\Foundation\Console\Commands\Database\Migration\MigrationMakeCommand;
use Laventure\Foundation\Console\Commands\Database\Migration\MigrationMigrateCommand;
use Laventure\Foundation\Console\Commands\Database\Migration\MigrationResetCommand;
use Laventure\Foundation\Console\Commands\Database\Migration\MigrationRollbackCommand;
use Laventure\Foundation\Console\Commands\Database\ORM\Fixtures\FixtureLoadCommand;
use Laventure\Foundation\Console\Commands\Database\ORM\Fixtures\FixtureMakeCommand;
use Laventure\Foundation\Console\Commands\Dotenv\GenerateEnvCommand;
use Laventure\Foundation\Console\Commands\Dotenv\GenerateKeyCommand;
use Laventure\Foundation\Console\Commands\Routing\Controller\MakeControllerCommand;
use Laventure\Foundation\Console\Commands\Server\ServerRunCommand;
use Laventure\Foundation\Console\Commands\Server\ServerStartCommand;


/**
 * @Kernel
 */
class Kernel implements ConsoleKernelContract
{


    /**
     * @var Application
     */
    protected $app;





    /**
     * @var Console
    */
    protected $console;




    /**
     * Application commands
     *
     * @var string[]
    */
    protected $commands = [];




    /**
     * @var array
    */
    protected $defaultCommands = [
       // Command
       MakeCommand::class,
       // Server
       ServerRunCommand::class,
       // Database
       FixtureMakeCommand::class,
       FixtureLoadCommand::class,
       // Migrations
       MigrationMakeCommand::class,
       MigrationInstallCommand::class,
       MigrationMigrateCommand::class,
       MigrationRollbackCommand::class,
       MigrationResetCommand::class,
       // Dotenv
       GenerateEnvCommand::class,
       GenerateKeyCommand::class,
       // Routing
       MakeControllerCommand::class,
    ];




    /**
     * Kernel constructor.
     *
     * @param Application $app
     * @param Console $console
    */
    public function __construct(Application $app, Console $console)
    {
          $this->app     = $app;
          $this->console = $console;
    }





    /**
     * @inheritDoc
    */
    public function handle(InputInterface $input, OutputInterface $output)
    {
        try {

            $this->loadCommands();

            return $this->console->run($input, $output);

        } catch (\Exception $e) {

            trigger_error($e->getMessage());
        }
    }



    /**
     * @return void
    */
    protected function loadCommands()
    {
         $commands = $this->getCommandStack();

         $this->app['@command.loader']->loadResolvedCommands($commands);
    }



    /**
     * @inheritDoc
    */
    public function terminate(InputInterface $input, $status)
    {
          $this->console->echo();

          return $status;
    }




    /**
     * @return array
    */
    protected function getCommandStack(): array
    {
         return array_merge($this->defaultCommands, $this->commands);
    }
}