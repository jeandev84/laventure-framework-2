<?php
namespace Laventure\Component\Console\Command;

use Laventure\Component\Console\Input\InputInterface;
use Laventure\Component\Console\Output\OutputInterface;


/**
 * @UndoableCommandInterface
 */
interface UndoableCommandInterface extends CommandInterface
{

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return mixed
    */
    public function undo(InputInterface $input, OutputInterface $output);
}