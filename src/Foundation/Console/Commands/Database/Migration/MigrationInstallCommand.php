<?php
namespace Laventure\Foundation\Console\Commands\Database\Migration;


use Laventure\Component\Console\Command\Command;
use Laventure\Component\Console\Input\InputInterface;
use Laventure\Component\Console\Output\OutputInterface;
use Laventure\Component\Database\Migration\Migrator;
use Laventure\Foundation\Console\Commands\Database\Migration\Common\AbstractMigrationCommand;



/**
 * @MigrationInstallCommand
*/
class MigrationInstallCommand extends AbstractMigrationCommand
{


    /**
     * @var string
    */
    protected $name = 'migration:install';



    /**
     * @var string
    */
    protected $description = "create the migrator table .";


    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
    */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->migrator->createMigrationTable();

        foreach ($this->migrator->getLogMessages() as $message) {
            $output->writeln($message);
        }

        return Command::SUCCESS;
    }
}