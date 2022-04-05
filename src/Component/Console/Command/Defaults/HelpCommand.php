<?php
namespace Laventure\Component\Console\Command\Defaults;


use Laventure\Component\Console\Command\Command;
use Laventure\Component\Console\Input\InputInterface;
use Laventure\Component\Console\Output\OutputInterface;


/**
 * @HelpCommand
*/
class HelpCommand extends Command
{

    /**
     * @var string
    */
    protected $defaultName = 'help';



    /**
     * @var string
    */
    protected $description = 'give more information each commands.';




    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|mixed
    */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("Help command");

        return Command::SUCCESS;
    }
}