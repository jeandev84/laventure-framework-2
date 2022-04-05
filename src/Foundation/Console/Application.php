<?php
namespace Laventure\Foundation\Console;


use Laventure\Component\Console\Console;
use Laventure\Component\Console\Input\InputInterface;
use Laventure\Component\Console\Output\OutputInterface;
use Laventure\Component\Container\Container;
use Laventure\Contract\Application\ApplicationInterface;




/**
 * @Application
*/
class Application extends Console implements ApplicationInterface
{


    /**
     * @var Container
    */
    protected $app;




    /**
     * @var string
    */
    protected $name = 'Laventure';






    /**
     * @var string
    */
    protected $version = '1.0';





    /**
     * Application console constructor.
     *
     * @param Container $app
     * @param array $commands
    */
    public function __construct(Container $app, array $commands = [])
    {
           parent::__construct($commands);
           $this->app = $app;
    }




    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return mixed|void
    */
    public function run(InputInterface $input, OutputInterface $output)
    {
          return parent::run($input, $output);
    }




    /**
     * @param Console $console
     * @return string
    */
    public function printHeaderInformation(Console $console): string
    {
           return "";
    }




    /**
     * @param Console $console
     * @return string
    */
    public function printFooterInformation(Console $console): string
    {
          return "";
    }





    /**
     * @param string $name
     * @return $this
    */
    public function name(string $name): self
    {
         $this->name = $name;

         return $this;
    }




    /**
     * @param string $version
     * @return $this
    */
    public function version(string $version): self
    {
         $this->version = $version;

         return $this;
    }




    /**
     * @inheritDoc
    */
    public function getName(): string
    {
        return $this->name;
    }





    /**
     * @inheritDoc
    */
    public function getVersion(): string
    {
        return $this->version;
    }

}