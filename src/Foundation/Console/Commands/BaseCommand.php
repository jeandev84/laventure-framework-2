<?php
namespace Laventure\Foundation\Console\Commands;


use Laventure\Component\Config\Config;
use Laventure\Component\Console\Command\Command;
use Laventure\Component\FileSystem\FileSystem;
use Laventure\Foundation\Application;


/**
 * @BaseCommand
*/
class BaseCommand extends Command
{

       /**
        * @var Application
       */
       protected $app;


       /**
        * @var FileSystem
       */
       protected $fileSystem;




       /**
        * @param Application $app
        * @param FileSystem $fileSystem
        * @param string|null $name
       */
       public function __construct(
           Application $app,
           FileSystem $fileSystem,
           string $name = null
       )
       {
           parent::__construct($name);
           $this->app = $app;
           $this->fileSystem = $fileSystem;
       }
}

