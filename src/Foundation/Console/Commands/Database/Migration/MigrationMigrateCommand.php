<?php
namespace Laventure\Foundation\Console\Commands\Database\Migration;


use Laventure\Component\Console\Command\Command;
use Laventure\Component\Console\Input\InputInterface;
use Laventure\Component\Console\Output\OutputInterface;
use Laventure\Foundation\Console\Commands\Database\Migration\Common\AbstractMigrationCommand;


/**
 * @MigrationMigrateCommand
*/
class MigrationMigrateCommand extends AbstractMigrationCommand
{

    /**
     * @var string
    */
    protected $name = 'migration:migrate';



    /**
     * @var string
    */
    protected $description = "migrate applied migrations.";




    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
    */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        if($this->migrator->migrate()) {
            foreach ($this->migrator->getLogMessages() as $message) {
                $output->writeln($message);
            }
        }

        return Command::SUCCESS;
    }
}