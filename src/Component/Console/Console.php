<?php
namespace Laventure\Component\Console;



use Closure;
use Laventure\Component\Console\Command\Command;
use Laventure\Component\Console\Command\Defaults\HelpCommand;
use Laventure\Component\Console\Command\Defaults\ListCommand;
use Laventure\Component\Console\Command\ListableCommandInterface;
use Laventure\Component\Console\Input\InputInterface;
use Laventure\Component\Console\Output\OutputInterface;
use Laventure\Component\Console\Output\Style\ConsoleStyle;


/**
 * Console command
 *
 * This class invoke command (Invoker Command)
 *
 * @Console
*/
class Console implements ConsoleInterface
{


    /**
     * collect commands
     *
     * @var Command[]
    */
    protected $commands = [];



    /**
     * collect default commands
     *
     * @var array
    */
    protected $defaultCommands = [];




    /**
     * @var array
    */
    protected $messages = [];




    /**
     * @var string
    */
    protected $fileScriptName = 'console';



    /**
     * @var ConsoleStyle
    */
    protected $style;




    /**
     * Console constructor
     *
     * @param Command[] $commands
    */
    public function __construct(array $commands = [])
    {
         $defaultCommands = $this->getDefaultCommands();

         if ($commands) {
             $defaultCommands = $commands;
         }

         $this->setDefaultCommands($defaultCommands);

         $this->style = new ConsoleStyle();
    }




     /**
      * @return ConsoleStyle
     */
     public function getStyle(): ConsoleStyle
     {
         return $this->style;
     }



     /**
      * @param string $filename
      * @return $this
     */
     public function scriptFilename(string $filename): self
     {
          $this->fileScriptName = $filename;

          return $this;
     }




     /**
      * @param $name
      * @return bool
     */
     public function isValidScriptFileName($name): bool
     {
         return $this->fileScriptName === $name;
     }





     /**
     * Set default commands
     *
     * @param array $commands
     * @return $this
    */
    public function setDefaultCommands(array $commands): self
    {
          foreach ($commands as $command) {
              $this->defaultCommands[$command->getName()] = $command;
          }

          $this->addCommands($commands);

          return $this;
    }



    /**
     * Add command
     *
     * @param Command $command
     * @return Command
    */
    public function addCommand(Command $command): Command
    {
        if (! $name = $command->getName()) {
             trigger_error("Command name inside class (". get_class($command) . ") is required.");
        }

        $this->commands[$name] = $command;

        return $command;
    }




    /**
     * Add command by given name
     *
     * @param string $name
     * @param Command $command
     * @return Command
    */
    public function add(string $name, Command $command): Command
    {
         $command->name($name);

         return $this->addCommand($command);
    }




    /**
     * Add collection commands
     *
     * @param Command[] $commands
     * @return void
    */
    public function addCommands(array $commands)
    {
        foreach ($commands as $command) {
            $this->addCommand($command);
        }
    }





    /**
     * Get collection commands
     *
     * @return Command[]
    */
    public function getCommands(): array
    {
        return $this->commands;
    }




    /**
     * Find command in the collection
     *
     * @param string $name
     * @return Command|null
    */
    public function getCommand($name): ?Command
    {
        return $this->commands[$name] ?? null;
    }






    /**
     * Determine if given name has  in collection
     *
     * @param string $name
     * @return bool
    */
    public function hasCommand(string $name): bool
    {
         return isset($this->commands[$name]);
    }




    /**
     * Remove command by given name.
     *
     * @param string $name
    */
    public function removeCommand(string $name)
    {
         unset($this->commands[$name]);
    }




    /**
     * Remove commands by name
     *
     * @param array $names
    */
    public function removeCommands(array $names)
    {
        foreach ($names as $name) {
            $this->removeCommand($name);
        }
    }




    /**
     * Add output messages
     *
     * @param array|string $message
     * @return $this
    */
    public function addMessages($message): self
    {
          $this->messages = array_merge($this->messages, (array) $message);

          return $this;
    }




    /**
     * Get output message
     *
     * @return array
    */
    public function getMessages(): array
    {
         return $this->messages;
    }



    /**
     * @param int $status
     * @return int
    */
    public function printMessages(int $status): int
    {
        $messages = $this->getMessages();

        switch ($status) {
            case Command::SUCCESS;
                $this->printSuccessMessages($messages);
                break;
            case Command::FAILURE;
                $this->printFailureMessages($messages);
                break;
            case Command::INVALID;
                $this->printInvalidMessages($messages);
                break;
            default:
        }

        return $status;
    }




    /**
     * @param array $messages
     * @return void
    */
    public function echo(array $messages = [])
    {
         if (! $messages) {
             $messages = $this->getMessages();
         }

         echo implode($messages);
    }




    /**
     * @return void
    */
    public function printSuccessMessages(array $messages)
    {
          // green
    }



    /**
     * @return void
    */
    public function printFailureMessages(array $messages)
    {
         // failure
    }



    /**
     * @return void
    */
    public function printInvalidMessages(array $messages)
    {
        // invalid
    }





    /**
     * Add command
     *
     * @param string $name
     * @param Closure $closure
     * @param string|null $description
     * @return Command
    */
    public function command(string $name, Closure $closure, string $description = null): Command
    {
         $command = $this->makeCommand($name);

         $closure($command);

         $command->description($description);

         return $this->add($name, $command);
    }


    


    /**
     * @param string $name
     * @param string|null $description
     * @return Command
    */
    public function makeCommand(string $name, string $description = null): Command
    {
        $command = new Command($name);
        
        if ($description) {
            $command->description($description);   
        }
        
        return $command;
    }




    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
    */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $commandName = $input->getFirstArgument();

        if (! $commandName) {
            $commandName = 'list';
        }elseif (\in_array($commandName, ['-h', '--help'])) {
            $commandName = 'help';
        }


        if (! $this->hasCommand($commandName)) {
            exit("Invalid command name '{$commandName}'\n");
        }

        $command = $this->getCommand($commandName);

        if ($command instanceof ListableCommandInterface) {
            $command->setCommands($this->getCommands());
        }

        $status = $command->terminate($input, $output);

        $this->addMessages($output->getMessages());

        return $status;
    }




    /**
     * Execute command
     *
     * @inheritDoc
    */
    public function run(InputInterface $input, OutputInterface $output)
    {
         $this->printHeaderInformation($this);
         $status = $this->execute($input, $output);
         $this->printFooterInformation($this);
         return $status;
    }





    /**
     * @param Console $console
     * @return void
    */
    public function printHeaderInformation(Console $console) {}





    /**
     * @param Console $console
     * @return void
    */
    public function printFooterInformation(Console $console) {}





    /**
     * Get default commands
     *
     * @return Command[]
    */
    public function getDefaultCommands(): array
    {
         return [
             new ListCommand(),
             new HelpCommand()
         ];
    }




    /**
     * @param $name
     * @return mixed|string
    */
    public function getDefaultCommand($name)
    {
         return $this->defaultCommands[$name] ?? '';
    }



    /**
     * @param $name
     * @return bool
    */
    public function isListCommand($name): bool
    {
        return $name === 'list';
    }



    /**
     * @param $name
     * @return bool
    */
    public function isHelpCommand($name): bool
    {
        return \in_array($name, ['-h', '--help', 'help']);
    }



    protected function exampleStyle()
    {
       /*
        $style = new ConsoleStyle();
        echo $this->style->styleBackground(
           "Invalid command name '{$commandName}'\n",
           "green"
        );
       */
    }
}