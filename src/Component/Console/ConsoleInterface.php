<?php
namespace Laventure\Component\Console;


use Laventure\Component\Console\Command\CommandInterface;
use Laventure\Component\Console\Input\InputInterface;
use Laventure\Component\Console\Output\OutputInterface;

/**
 * @ConsoleInterface
*/
interface ConsoleInterface
{

      /**
       * @return mixed
      */
      public function getCommands(): array;




      /**
        * @param $name
        * @return mixed
      */
      public function getCommand($name);






      /**
       * Execute command
       *
       * @param InputInterface $input
       * @param OutputInterface $output
       * @return mixed
      */
      public function run(InputInterface $input, OutputInterface $output);
}