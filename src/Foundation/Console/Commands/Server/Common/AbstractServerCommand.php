<?php
namespace Laventure\Foundation\Console\Commands\Server\Common;


use Laventure\Component\FileSystem\FileSystem;
use Laventure\Foundation\Application;
use Laventure\Foundation\Console\Commands\BaseCommand;



/**
 * @AbstractServerCommand
*/
abstract class AbstractServerCommand extends BaseCommand
{

        /**
         * Default host where server local will be run
         *
         * @var string
        */
        protected $host = '127.0.0.1:8000';


        /**
         * Target link where you can browse in your navigator
         *
         * @var string
        */
        protected $link = 'http://localhost:8000';



        /**
         * Default port target
         *
         * @var string
        */
        protected $port = '8000';




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
             parent::__construct($app, $fileSystem, $name);
        }




        /**
         * @param string $host
         * @return $this
        */
        protected function withHost(string $host): self
        {
             $this->host = $host;

             return $this;
        }



        /**
         * @return string
         */
        protected function getHost(): string
        {
            return $this->host;
        }




        /**
         * @param string $link
         * @return $this
         */
        protected function withLink(string $link): self
        {
            $this->link = $link;

            return $this;
        }




        /**
         * @return string
         */
        protected function getLink(): string
        {
            return $this->link;
        }




        /**
         * @param string $port
         * @return $this
         */
        protected function withPort(string $port): self
        {
            $this->port = $port;

            return $this;
        }



        /**
         * @return string
         */
        protected function getPort(): string
        {
            return $this->port;
        }


        /**
         * Run server
         */
        public function __destruct()
        {
            /*
            if ($this->fileSystem->exists('bin/127.0.0.1:8000')) {
                // dd($this->fileSystem->read('bin/127.0.0.1:8000'));
                $status = exec(
                    sprintf('php -S %s -t public -d display_errors=1',
                       $this->fileSystem->read('bin/127.0.0.1:8000')
                    )
                );
            }
            */
        }
}