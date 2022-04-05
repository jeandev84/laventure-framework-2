<?php
namespace Laventure\Component\Console\Command\Defaults;


use Laventure\Component\Console\Command\Command;
use Laventure\Component\Console\Command\CommandInterface;
use Laventure\Component\Console\Command\ListableCommandInterface;
use Laventure\Component\Console\Input\InputInterface;
use Laventure\Component\Console\Output\OutputInterface;



/**
 * @ListCommand
*/
class ListCommand extends Command implements ListableCommandInterface
{


    /**
     * @var string
    */
    protected $defaultName = 'list';




    /**
     * @var string
    */
    protected $description = 'describe all available commands of the system.';




    /**
     * @var Command[]
    */
    protected $commands = [];



    /**
     * @inheritDoc
    */
    public function setCommands(array $commands)
    {
          unset($commands[$this->defaultName]);

          $this->commands = $commands;
    }




    /**
     * @inheritDoc
    */
    public function getCommands(): array
    {
         return $this->commands;
    }




    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
    */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
         $output->writeln("List command.");

         foreach ($this->getCommands() as $command) {
              $output->writeln($command->getName() . " : " . $command->getDescription());
         }

         return Command::SUCCESS;
    }

}