<?php
namespace Laventure\Component\Console\Input;




use Laventure\Component\Console\Input\Collection\InputArgument;
use Laventure\Component\Console\Input\Collection\InputOption;
use Laventure\Component\Console\Output\OutputInterface;


/**
 * @InputArgv
*/
abstract class InputArgv implements InputInterface
{

    /**
     * @var string
    */
    protected $firstArgument;



    /**
     * @var array
    */
    protected $tokens = [];




    /**
     * parses arguments
     *
     * @var array
     */
    protected $arguments = [];




    /**
     * parses options
     *
     * @var array
    */
    protected $options = [];




    /**
     * @var array
    */
    protected $shortcuts = [];




    /**
     * @var array
    */
    protected $flags = [];




    /**
     * @var InputArgument[]
    */
    protected $argumentBag = [];




    /**
     * @var InputOption[]
    */
    protected $optionBag = [];




    /**
     * @var OutputInterface
    */
    protected $output;




    /**
     * @var bool
    */
    protected $interactive = false;



    /**
     * @var
    */
    protected $scriptName;




    /**
     * InputArgv constructor.
     *
     * @param array $tokens
    */
    public function __construct(array $tokens)
    {
          $this->tokens = $tokens;

          $this->beforeParseTokens($tokens);
    }




    /**
     * @param string $name
     * @return void
    */
    public function setScriptName(string $name)
    {
          $this->scriptName = $name;
    }




    /**
     * @inheritDoc
    */
    public function setArgumentBag(array $arguments)
    {
         foreach ($arguments as $argument) {
              if ($argument instanceof InputArgument) {
                  $this->argumentBag[$argument->getName()] = $argument;
              }
         }
    }



    /**
     * @inheritDoc
    */
    public function setOptionBag(array $options)
    {
         foreach ($options as $option) {
             if ($option instanceof InputOption) {
                 $this->optionBag[$option->getName()] = $option;
             }
         }
    }




    /**
     * Check important argument before parsing tokens
     *
     * @param array $tokens
     * @return void
    */
    private function beforeParseTokens(array $tokens)
    {
          /*
          if ($tokens[0] === $this->scriptName){
               $this->setInteractiveStatus(true);
          }
          */

          // php console make:command
          array_shift($tokens); // make:command

          if (isset($tokens[0])) {
              $this->setFirstArgument($tokens[0]);
          }

          array_shift($tokens);
          $this->parseTokens($tokens);
    }




    /**
     * @inheritDoc
    */
    public function setConsoleOutput(OutputInterface $output)
    {
          $this->output = $output;
    }



    /**
     * @inheritDoc
    */
    public function getConsoleOutput(): OutputInterface
    {
         return $this->output;
    }



    /**
     * @return bool
    */
    public function validInteractive(): bool
    {
         return $this->interactive;
    }




    /**
     * @param bool $status
     * @return void
    */
    protected function setInteractiveStatus(bool $status)
    {
         $this->interactive = $status;
    }



    /**
     * @return int
    */
    public function count(): int
    {
        return count($this->tokens);
    }





    /**
     * @inheritDoc
     */
    public function getTokens()
    {
        return $this->tokens;
    }





    /**
     * @param $argument
     * @return void
    */
    protected function setFirstArgument($argument)
    {
        $this->firstArgument = $argument;
    }



    /**
     * @inheritDoc
    */
    public function getFirstArgument()
    {
        return $this->firstArgument;
    }




    /**
     * Determine if the given exist in parses arguments
     *
     * @param $name
     * @return bool
    */
    public function hasArgument($name): bool
    {
         return isset($this->arguments[$name]);
    }




    /**
     * @inheritDoc
    */
    public function getArgument($name = null)
    {
          if ($this->hasArgument($name)) {
               return $this->arguments[$name];
          }

          if (empty($this->arguments)) {
              exit("Default argument is required.\n");
          }

          return $this->arguments[0];
    }





    /**
     * @inheritDoc
    */
    public function getArguments(): array
    {
         return $this->arguments;
    }




    /**
     * Determine if the given name exist in parses options.
     *
     * @param $name
     * @return bool
    */
    public function hasOption($name): bool
    {
         return isset($this->options[$name]);
    }





    /**
     * @inheritDoc
    */
    public function getOption($name)
    {
         return $this->options[$name] ?? null;
    }




    /**
     * @inheritDoc
    */
    public function getOptions()
    {
        return $this->options;
    }





    /**
     * @inheritDoc
    */
    abstract public function parseTokens(array $tokens);
}