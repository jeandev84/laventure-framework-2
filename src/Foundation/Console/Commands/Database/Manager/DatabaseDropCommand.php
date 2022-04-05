<?php
namespace Laventure\Foundation\Console\Commands\Database\Manager;


use Laventure\Component\Console\Command\Command;
use Laventure\Component\Console\Input\InputInterface;
use Laventure\Component\Console\Output\OutputInterface;


/**
 * @DatabaseDropCommand
*/
class DatabaseDropCommand extends Command
{

    /**
     * @var string
    */
    protected $name = 'database:drop';



    /**
     * @var string
    */
    protected $description = 'drop a database.';





    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
    */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        echo "Run process dropping database.\n";

        return Command::SUCCESS;
    }

}