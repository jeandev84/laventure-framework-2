<?php
namespace Laventure\Foundation\Console\Commands\Database\Migration;

use Laventure\Component\Console\Command\Command;
use Laventure\Component\Console\Input\InputInterface;
use Laventure\Component\Console\Output\OutputInterface;
use Laventure\Foundation\Console\Commands\Database\Migration\Common\AbstractMigrationCommand;



/**
 * @MigrationMakeCommand
*/
class MigrationMakeCommand extends AbstractMigrationCommand
{

    /**
     * @var string
    */
    protected $name = 'make:migration';




    /**
     * @var string
    */
    protected $description = "Generate a migration file.";



    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
    */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
          $migrationName = $this->generateMigrationName();

          if($this->generator->generate($migrationName)) {

              $migrationPath = $this->generator->loadMigrationPath($migrationName);
              $migrationNamespace = $this->generator->loadNamespace($migrationName);

              $output->writeln("New file migration generated successfully :");
              $output->writeln($migrationPath);
              $output->writeln($migrationNamespace);
          }

          return Command::SUCCESS;
    }




    /**
     * @return string
    */
    protected function generateMigrationName(): string
    {
         return sprintf('Version%s', date('YmdHis'));
    }
}