<?php
namespace Laventure\Component\Console\Output;


/**
 * @OutputInterface
*/
interface OutputInterface
{
      /**
        * write inline message without end of line.
        *
        * @param string $message
        * @return mixed
      */
      public function write(string $message);




      /**
       * write message with end of line
       *
       * @param string $message
       * @return mixed
      */
      public function writeln(string $message);




      /**
       * Execute command shell
       *
       * @param string $command
       * @return mixed
      */
      public function exec(string $command);




      /**
       * Get output message
       *
       * @return mixed
      */
      public function getMessages();
}