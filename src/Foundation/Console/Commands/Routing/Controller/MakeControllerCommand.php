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
     * $ php console make:controller DemoController
     * $ php console make:controller DemoController --resource (for creating resource)
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
    */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
           $controllerName = $input->getArgument();

           if ($input->hasFlag('resource')) {
                $generated = $this->generator->generateControllerResourceWeb($controllerName);
           } else {
                $generated = $this->generator->generateController($controllerName);
           }

           if ($generated) {

               if ($generatedPaths = $this->generator->getGeneratedPaths()) {
                   $output->writeln("Controller successfully generated : ");
                   foreach ($generatedPaths as $generatedPath) {
                       $output->writeln($generatedPath);
                   }
               }
           }

           return Command::SUCCESS;
    }


}