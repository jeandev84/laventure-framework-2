<?php
namespace Laventure\Component\Console\Command;


use Laventure\Component\Console\Input\Collection\InputArgument;
use Laventure\Component\Console\Input\Collection\InputCollection;
use Laventure\Component\Console\Input\Collection\InputCollectionInterface;
use Laventure\Component\Console\Input\Collection\InputOption;
use Laventure\Component\Console\Input\InputInterface;
use Laventure\Component\Console\Output\OutputInterface;


/**
 * @Command
*/
class Command implements CommandInterface
{


       const SUCCESS  = 0;
       const FAILURE  = 1;
       const INVALID  = 2;



      /**
       * Default name command.
       *
       * @var string
      */
      protected $defaultName;





      /**
       * Command name
       *
       * @var string
      */
      protected $name;





      /**
       * Command description
       *
       * @var string
      */
      protected $description = 'default description command';





      /**
       * Command help
       *
       * @var string
      */
      protected $help = 'default help command';




      /**
       * Input collections
       *
       * @var InputCollection
      */
      protected $definitions;





      /**
       * @param string|null $name
      */
      public function __construct(string $name = null)
      {
            $this->definition(new InputCollection());

            if ($name) {
                $this->name($name);
            }

            $this->configure();
      }




      /**
       * Configure command
       *
       * @return void
      */
      public function configure() {}





      /**
       * Set Input collections
       *
       * @param InputCollectionInterface $definitions
       * @return $this
      */
      public function definition(InputCollectionInterface $definitions): self
      {
            $this->definitions = $definitions;

            return $this;
      }




      /**
       * @param string $name
       * @return $this
      */
      public function defaultName(string $name): self
      {
          $this->defaultName = $name;

          return $this;
      }



      /**
       * Set command name
       *
       * @param string $name
       * @return $this
      */
      public function name(string $name): self
      {
          if (! $this->isValidName($name)) {
              return $this->defaultName($name);
          }

          $this->name = $name;

          return $this;
      }




      /**
       * @inheritDoc
      */
      public function getName()
      {
          return $this->name ?? $this->defaultName;
      }





      /**
       * Command description
       *
       * @param string $description
       * @return $this
      */
      public function description(string $description): self
      {
          $this->description = $description;

          return $this;
      }



      /**
       * @return string
      */
      public function getDescription(): string
      {
            return $this->description;
      }




      /**
       * Set help command
       *
       * @param string $help
       * @return $this
      */
      public function help(string $help): self
      {
           $this->help = $help;

           return $this;
      }




      /**
       * @return string
      */
      public function getHelp(): string
      {
           return $this->help;
      }



      /**
       * Collect input argument
       *
       * @param string $name
       * @param int|null $mode
       * @param string $description
       * @param string $default
       * @return $this
      */
      public function argument(string $name, int $mode = null, string $description = '', string $default = ''): self
      {
            $this->definitions->addArgument(
                new InputArgument(
                    $name,
                    $mode,
                    $description,
                    $default
                )
            );

            return $this;
      }



      /**
       * Get definition arguments
       *
       * @return array
      */
      public function getArguments(): array
      {
           return $this->definitions->getArguments();
      }


      /**
       * Collect input option
       *
       * @param string $name
       * @param int|null $mode
       * @param string $description
       * @param string $default
       * @return $this
      */
      public function option(string $name, int $mode = null, string $description = '', string $default = ''): self
      {
             $this->definitions->addOption(
                 new InputOption(
                     $name,
                     $mode,
                     $description,
                     $default
                 )
             );

             return $this;
      }




      /**
       * @return InputOption[]
      */
      public function getOptions(): array
      {
           return $this->definitions->getOptions();
      }



      /**
       * @inheritDoc
      */
      public function execute(InputInterface $input, OutputInterface $output): int
      {
            return trigger_error('You must override the execute() method in the concrete command class.'. get_called_class());
      }




      /**
       * Terminate execution command.
       *
       * @param InputInterface $input
       * @param OutputInterface $output
       * @return int
      */
      public function terminate(InputInterface $input, OutputInterface $output): int
      {
           $input->setArgumentBag($this->getArguments());
           $input->setOptionBag($this->getOptions());

           return $this->execute($input, $output);
      }





     /**
      * @param $name
      * @return string
     */
     private function resolveName($name): string
     {
        if (! $this->isValidName($name)) {
            trigger_error("Invalid command name '{$name}'.");
        }

        return $name;
    }




     /**
      * @param $name
      * @return false
     */
     private function isValidName($name): bool
     {
         return stripos($name, ':') !== false;
     }
}