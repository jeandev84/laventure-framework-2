<?php
namespace Laventure\Component\Console\Input;


use Laventure\Component\Console\Input\Collection\InputArgument;
use Laventure\Component\Console\Input\Collection\InputOption;
use Laventure\Component\Console\Output\OutputInterface;


/**
 * @InputInterface
*/
interface InputInterface
{
    /**
     * This method return all parses tokens
     *
     * Example: php console test a=1 b=3 -c=foo --d=bar -m --n
     *
     * @return mixed
    */
    public function getTokens();



    /**
     * This method return first parsed argument
     *
     * Example: php console myFirstArgument
     *
     * @return mixed
    */
    public function getFirstArgument();






    /**
     * This  method implements setting arguments, options, flags ...
     *
     * @param array $tokens
     * @return mixed
    */
    public function parseTokens(array $tokens);




    /**
     * Get parses arguments
     *
     * @return mixed
    */
    public function getArguments();





    /**
     * Get one or all pares arguments
     *
     * @return mixed
    */
    public function getArgument($name = null);






    /**
     * Get parsed option value
     *
     * @param $name
     * @return mixed
    */
    public function getOption($name);




    /**
     * Get parses options
     *
     * @return mixed
    */
    public function getOptions();






    /**
     * Set arguments from definition
     *
     * @param InputArgument[] $arguments
     * @return mixed
    */
    public function setArgumentBag(array $arguments);




    /**
     * Set options from definition
     *
     * @param InputOption[] $options
     * @return mixed
    */
    public function setOptionBag(array $options);



    /**
     * @param OutputInterface $output
     * @return mixed
    */
    public function setConsoleOutput(OutputInterface $output);




    /**
     * @return OutputInterface
    */
    public function getConsoleOutput(): OutputInterface;





    /**
     * @return bool
    */
    public function validInteractive(): bool;




    /**
     * Name of script file to execute via terminal
     *
     * @param string $name
     * @return mixed
    */
    public function setScriptName(string $name);

}