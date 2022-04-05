<?php
namespace Laventure\Foundation\Console\Commands\Database\Migration;


use Laventure\Component\Console\Command\Command;
use Laventure\Component\Console\Input\InputInterface;
use Laventure\Component\Console\Output\OutputInterface;
use Laventure\Component\Container\Container;
use Laventure\Component\Database\Migration\Migrator;
use Laventure\Component\FileSystem\FileSystem;
use Laventure\Foundation\Console\Commands\Database\Migration\Common\AbstractMigrationCommand;


/**
 * @MigrationResetCommand
*/
class MigrationResetCommand extends AbstractMigrationCommand
{

    /**
     * @var string
    */
    protected $name = 'migration:reset';





    /**
     * @var string
    */
    protected $description = "reset all applied migrations .";





    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
    */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
         $this->migrator->reset();
         $output->writeln("Migrations successfully reset.");

         return Command::SUCCESS;
    }


}