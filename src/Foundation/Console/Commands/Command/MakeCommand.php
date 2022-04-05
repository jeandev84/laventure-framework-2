<?php
namespace Laventure\Foundation\Console\Commands\Command;


use Laventure\Component\Console\Command\Command;
use Laventure\Component\Console\Input\InputInterface;
use Laventure\Component\Console\Output\OutputInterface;
use Laventure\Foundation\Generators\CommandGenerator;


/**
 * @MakeCommand
*/
class MakeCommand extends Command
{

    /**
     * @var string
     */
    protected $name = 'make:command';



    /**
     * @var string
    */
    protected $description = "generate new command ...";




    /**
     * @var CommandGenerator
    */
    protected $generator;




    /**
     * @param string|null $name
    */
    public function __construct(CommandGenerator $generator, string $name = null)
    {
          parent::__construct($name);
          $this->generator = $generator;
    }




    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
    */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $commandName = $input->getArgument();

        if ($this->generator->generate($commandName)) {
             $output->writeln("Command {$commandName} successfully generated.");
             $output->writeln($this->generator->getGeneratedPath());
        }

        return Command::SUCCESS;
    }

}