<?php
namespace Laventure\Component\Console\Command;





/**
 * @ListableCommandInterface
*/
interface ListableCommandInterface extends CommandInterface
{

     /**
      * Set commands
      *
      * @param CommandInterface[] $commands
      * @return mixed
     */
     public function setCommands(array $commands);



     /**
      * Get command list
      *
      * @return CommandInterface[]
     */
     public function getCommands(): array;
}