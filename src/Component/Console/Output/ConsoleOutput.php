<?php
namespace Laventure\Component\Console\Output;



use Laventure\Component\Console\Output\Style\ConsoleStyle;

/**
 * @ConsoleOutput
*/
class ConsoleOutput implements OutputInterface
{


    /**
     * collect out put message
     *
     * @var string[]
    */
    protected $messages = [];




    /**
     * Collection command to execute
     *
     * @var array
    */
    protected $command = [];



    /**
     * Console style
     *
     * @var ConsoleStyle
    */
    protected $style;



    public function __construct()
    {
         $this->style = new ConsoleStyle();
    }



    /**
     * @inheritDoc
    */
    public function write(string $message): self
    {
        $this->messages[] = $message;

        return $this;
    }




    /**
     * @inheritDoc
    */
    public function writeln(string $message)
    {
         $message = sprintf('%s%s', $message, "\n");

         return $this->write($message);
    }



    /**
     * @inheritDoc
    */
    public function getMessages()
    {
        return $this->messages;
    }




    /**
     * @inheritDoc
    */
    public function exec(string $command)
    {
        if ($this->messages) {
            echo implode($this->messages);
        }

        return shell_exec($command);
    }



    /**
     * @param $message
     * @return string
    */
    public function success($message): string
    {
         return $this->style->foregroundGreen($message);
    }


    /**
     * @param $message
     * @return void
    */
    public function info($message)
    {
         //
    }



    /**
     * @return ConsoleStyle
    */
    public function getStyle(): ConsoleStyle
    {
         return $this->style;
    }




    /**
     * @param $message
     * @return void
    */
    public function writeWithTimestamp($message)
    {
        $this->writeln(sprintf('%s %s', '['. date('Y-m-d H:i:s') .'] ', $message));
    }
}