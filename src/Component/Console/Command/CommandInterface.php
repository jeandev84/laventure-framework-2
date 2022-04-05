<?php
namespace Laventure\Component\Console\Command;


use Laventure\Component\Console\Input\InputInterface;
use Laventure\Component\Console\Output\OutputInterface;


/**
 * @CommandInterface
*/
interface CommandInterface
{

     /**
      * @return mixed
     */
     public function getName();



     /**
      * Execute command
      *
      * @param InputInterface $input
      * @param OutputInterface $output
      * @return mixed
     */
     public function execute(InputInterface $input, OutputInterface $output);
}