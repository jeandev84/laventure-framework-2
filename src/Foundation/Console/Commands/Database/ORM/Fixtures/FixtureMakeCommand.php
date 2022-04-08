<?php
namespace Laventure\Foundation\Console\Commands\Database\ORM\Fixtures;


use Laventure\Component\Console\Command\Command;
use Laventure\Component\Console\Input\InputInterface;
use Laventure\Component\Console\Output\OutputInterface;
use Laventure\Component\FileSystem\FileSystem;
use Laventure\Foundation\Application;
use Laventure\Foundation\Console\Commands\BaseCommand;
use Laventure\Foundation\Generator\FixtureGenerator;


/**
 * @FixtureMakeCommand
*/
class FixtureMakeCommand extends BaseCommand
{

    /**
     * @var string
    */
    protected $name = 'orm:fixtures:make';




    /**
     * @var string
    */
    protected $description = 'make a new fixture. php console make:fixture';




    /**
     * @var FixtureGenerator
    */
    protected $generator;





    /**
     * @param Application $app
     * @param FileSystem $fileSystem
     * @param FixtureGenerator $generator
     * @param string|null $name
    */
    public function __construct(
        Application $app,
        FileSystem $fileSystem,
        FixtureGenerator $generator,
        string $name = null
    )
    {
         parent::__construct($app, $fileSystem, $name);
         $this->generator = $generator;
    }




    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
    */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
          if ($this->generator->generateFixture($input->getArgument())) {
               $output->writeln("New fixture successfully generated :");
               $output->writeln($this->generator->getGeneratedPath());
          }

          return Command::SUCCESS;
    }

}