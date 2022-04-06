<?php
namespace Laventure\Foundation\Console\Commands\Routing\Resource;


use Laventure\Component\Console\Command\Command;
use Laventure\Component\Console\Input\InputInterface;
use Laventure\Component\Console\Output\OutputInterface;
use Laventure\Foundation\Console\Commands\Routing\AbstractResourceCommand;



/**
 * @MakeResourceCommand
*/
class MakeResourceCommand extends AbstractResourceCommand
{

    /**
     * @var string
     */
    protected $name = 'make:resource';




    /**
     * @var string
    */
    protected $description = 'make a new resource. make:resource or make:resource --api';




    /**
     * Example: $ php console make:resource Product
     * Example: $ php console make:resource Product --api (make resource api)
     *
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
    */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
           $entityClass = $input->getArgument();

           if ($input->hasFlag('api')) {
               $generated = false;
           }else {
               $generated = $this->generator->generateResourceWeb($entityClass);
           }


           if ($generated) {
               $output->writeln("Controller successfully generated : ");
               foreach ($this->generator->getGeneratedPaths() as $path) {
                   $output->writeln($path);
               }
           }

           return Command::SUCCESS;
    }

}