<?php
namespace Laventure\Foundation\Console\Commands\Database\ORM\Repository;


use Laventure\Component\Console\Command\Command;
use Laventure\Component\Console\Input\InputInterface;
use Laventure\Component\Console\Output\OutputInterface;
use Laventure\Foundation\Generator\EntityGenerator;



/**
 * @MakeEntityCommand
*/
class MakeEntityCommand extends Command
{


    /**
     * @var string
    */
    protected $name = 'make:entity';




    /**
     * @var string
    */
    protected $description = "make entity class ...";





    /**
     * @var EntityGenerator
    */
    protected $generator;




    /**
     * MakeEntityCommand constructor.
     *
     * @param EntityGenerator $generator
     * @param string|null $name
    */
    public function __construct(
        EntityGenerator $generator,
        string $name = null
    )
    {
        parent::__construct($name);
        $this->generator = $generator;
    }




    /**
     * Execute command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
    */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($this->generator->generate($input->getArgument())) {
            $output->writeln("New files successfully generated : ");
            foreach ($this->generator->getGeneratedPaths() as $generatedPath) {
                $output->writeln($generatedPath);
            }
        }

        return Command::SUCCESS;
    }
}