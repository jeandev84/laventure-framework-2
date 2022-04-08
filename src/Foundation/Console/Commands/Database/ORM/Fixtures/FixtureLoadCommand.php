<?php
namespace Laventure\Foundation\Console\Commands\Database\ORM\Fixtures;


use Laventure\Component\Console\Command\Command;
use Laventure\Component\Console\Input\InputInterface;
use Laventure\Component\Console\Output\OutputInterface;
use Laventure\Foundation\Loader\FixtureLoader;


/**
 * @FixtureLoadCommand
*/
class FixtureLoadCommand extends Command
{

    /**
     * @var string
    */
    protected $name = 'orm:fixtures:load';




    /**
     * @var string
    */
    protected $description = 'make a new fixture. php console make:fixture';




    /**
     * @var FixtureLoader
    */
    protected $loader;




    /**
     * @param FixtureLoader $loader
     * @param string|null $name
    */
    public function __construct(FixtureLoader $loader, string $name = null)
    {
         parent::__construct($name);
         $this->loader = $loader;
    }




    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
    */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
         $this->loader->loadFixtures();

         foreach ($this->loader->getLogMessages() as $message) {
              $output->writeln($message);
         }

         return Command::SUCCESS;
    }
}