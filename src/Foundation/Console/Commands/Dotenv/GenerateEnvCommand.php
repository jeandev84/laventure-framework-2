<?php
namespace Laventure\Foundation\Console\Commands\Dotenv;


use Laventure\Component\Console\Command\Command;
use Laventure\Component\Console\Input\InputInterface;
use Laventure\Component\Console\Output\OutputInterface;
use Laventure\Foundation\Console\Commands\BaseCommand;


/**
 * @GenerateEnvCommand
*/
class GenerateEnvCommand extends BaseCommand
{

    /**
     * @var string
    */
    protected $name = 'env:generate';




    /**
     * @var string
    */
    protected $description = 'Generate a env file for configuration application.';





    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
    */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
         if ($this->fileSystem->copy('.env.example', '.env')) {
             $output->write("New file (.env) successfully generated.");
         }

         return Command::SUCCESS;
    }

}