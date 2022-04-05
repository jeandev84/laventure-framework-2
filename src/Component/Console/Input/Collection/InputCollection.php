<?php
namespace Laventure\Component\Console\Input\Collection;




/**
 * @InputCollection
*/
class InputCollection implements InputCollectionInterface
{


    /**
     * Collect arguments
     *
     * @var InputArgument[]
    */
    protected $arguments = [];




    /**
     * Collect options
     *
     * @var InputOption[]
    */
    protected $options = [];






    /**
     * Add argument
     *
     * @param InputArgument $argument
     * @return $this
    */
    public function addArgument(InputArgument $argument): self
    {
         $this->arguments[$argument->getName()] = $argument;

         return $this;
    }




    /**
     * Determine has argument by given name
     *
     * @param $name
     * @return bool
    */
    public function hasArgument($name): bool
    {
         return isset($this->arguments[$name]);
    }




    /**
     * Get argument
     *
     * @param $name
     * @return InputArgument|null
    */
    public function getArgument($name): ?InputArgument
    {
         return $this->arguments[$name] ?? null;
    }



    /**
     * @inheritDoc
     * @return InputArgument[]
    */
    public function getArguments(): array
    {
        return $this->arguments;
    }



    /**
     * Add option
     *
     * @param InputOption $option
     * @return $this
    */
    public function addOption(InputOption $option): self
    {
         $this->options[$option->getName()] = $option;

         return $this;
    }




    /**
     * Determine has option by given name
     *
     * @param $name
     * @return bool
    */
    public function hasOption($name): bool
    {
         return isset($this->options[$name]);
    }




    /**
     * Get option
     *
     * @param $name
     * @return InputOption|null
    */
    public function getOption($name): ?InputOption
    {
         return $this->options[$name] ?? null;
    }





    /**
     * @inheritDoc
     * @return InputOption[]
    */
    public function getOptions(): array
    {
         return $this->options;
    }
}