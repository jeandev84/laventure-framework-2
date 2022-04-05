<?php
namespace Laventure\Foundation\Console\Commands\Routing\Controller;


use Laventure\Component\Console\Command\Command;
use Laventure\Component\Console\Input\InputInterface;
use Laventure\Component\Console\Output\OutputInterface;
use Laventure\Foundation\Console\Commands\Routing\AbstractResourceCommand;



/**
 * @MakeControllerCommand
*/
class MakeControllerCommand extends AbstractResourceCommand
{

    /**
     * @var string
    */
    protected $name = 'make:controller';




    /**
     * @var string
    */
    protected $description = 'make a new controller. php console make:controller';




    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
    */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
           $controllerName = $input->getArgument();

           if ($this->generator->generateController($controllerName)) {
               $output->writeln("Controller successfully generated : ");
               $output->writeln($this->generator->getGeneratedPath());
           }

           return Command::SUCCESS;
    }


}