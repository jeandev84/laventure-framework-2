<?php
namespace Laventure\Foundation\Console\Commands\Database\Migration;


use Laventure\Component\Console\Command\Command;
use Laventure\Component\Console\Input\InputInterface;
use Laventure\Component\Console\Output\OutputInterface;
use Laventure\Foundation\Console\Commands\Database\Migration\Common\AbstractMigrationCommand;


/**
 * @MigrationRollbackCommand
*/
class MigrationRollbackCommand extends AbstractMigrationCommand
{


    /**
     * @var string
     */
    protected $name = 'migration:rollback';



    /**
     * @var string
    */
    protected $description = "rollback all migrations drop all created table and truncate the version table .";




    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
    */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        if($this->migrator->rollback()) {
           foreach ($this->migrator->getLogMessages() as $message) {
               $output->writeln($message);
           }
        }

        return Command::SUCCESS;
    }
}