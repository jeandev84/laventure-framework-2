<?php
namespace Laventure\Foundation;


use Laventure\Component\Container\Container;
use Laventure\Component\Container\Contract\ContainerInterface;
use Laventure\Component\Http\Request\Request;
use Laventure\Component\Http\Response\Response;
use Laventure\Contract\Application\ApplicationInterface;
use Laventure\Foundation\Provider\ApplicationServiceProvider;
use Laventure\Foundation\Provider\AssetServiceProvider;
use Laventure\Foundation\Provider\ConfigurationServiceProvider;
use Laventure\Foundation\Provider\ConsoleServiceProvider;
use Laventure\Foundation\Provider\DatabaseServiceProvider;
use Laventure\Foundation\Provider\ErrorHandlerServiceProvider;
use Laventure\Foundation\Provider\EventDispatcherServiceProvider;
use Laventure\Foundation\Provider\FileSystemServiceProvider;
use Laventure\Foundation\Provider\MiddlewareServiceProvider;
use Laventure\Foundation\Provider\MigrationServiceProvider;
use Laventure\Foundation\Provider\RouteServiceProvider;
use Laventure\Foundation\Provider\UrlGeneratorServiceProvider;
use Laventure\Foundation\Provider\ViewServiceProvider;


/**
 * Laventure application
 *
 *
 * @Application
*/
class Application extends Container implements ApplicationInterface
{

        /**
         * name of application
         *
         * @var string
        */
        protected $name = 'Laventure';




        /**
         * version of application
         *
         * @var string
        */
        protected $version = '1.0';




        /**
         * Base path of application
         *
         * @var string
        */
        protected $basePath;




        /**
         * Application constructor
         *
         * @param string|null $basePath
        */
        public function __construct(string $basePath = null)
        {
              if ($basePath) {
                 $this->path($basePath);
              }

              $this->registerBaseBindings();
              $this->registerBaseProviders();
        }



        /**
         * Register base bindings of application
         *
         * @return void
        */
        protected function registerBaseBindings()
        {
             self::setInstance($this);

             $this->instances([
                Container::class          => $this,
                ContainerInterface::class => $this,
                'app'                     => $this
             ]);
        }




        /**
         * Registration base providers
         *
         * @return void
        */
        protected function registerBaseProviders()
        {
            $this->addProviders([
                ApplicationServiceProvider::class,
                ErrorHandlerServiceProvider::class,
                FileSystemServiceProvider::class,
                ConfigurationServiceProvider::class,
                DatabaseServiceProvider::class,
                MiddlewareServiceProvider::class,
                EventDispatcherServiceProvider::class,
                RouteServiceProvider::class,
                AssetServiceProvider::class,
                UrlGeneratorServiceProvider::class,
                ViewServiceProvider::class,
                ConsoleServiceProvider::class
           ]);
        }




        /**
         * set base path of application
         *
         * @param string $path
         *
         * @return $this
        */
        public function path(string $path): self
        {
             $this->basePath = rtrim($path, '\\/');

             $this->instance('path', $this->basePath);
             $this->alias('@root', 'path');

             return $this;
        }






        /**
         * set name of application
         *
         * @param string $name
         * @return $this
        */
        public function name(string $name): self
        {
              $this->name = $name;

              return $this;
        }





        /**
         * set version of application
         *
         * @param string $version
         * @return $this
        */
        public function version(string $version): self
        {
             $this->version = $version;

             return $this;
        }




        /**
         * @inheritDoc
        */
        public function getName(): string
        {
             return $this->name;
        }




        /**
         * @inheritDoc
        */
        public function getVersion(): string
        {
            return $this->version;
        }





        /**
         * @return string
        */
        public function getPath(): string
        {
             return $this->basePath;
        }



        /**
         * Pipe Http Kernel
         *
         * @param string $kernelClass
         * @return $this
        */
        public function pipeHttpKernel(string $kernelClass): self
        {
            $this->singleton(\Laventure\Contract\Http\Kernel::class, $kernelClass);

            return $this;
        }





        /**
         * Pipe Console Kernel
         *
         * @param string $kernelClass
         * @return $this
        */
        public function pipeConsoleKernel(string $kernelClass): self
        {
            $this->singleton(\Laventure\Contract\Console\Kernel::class, $kernelClass);

            return $this;
        }



        /**
          * Pipe Exception Handler
          *
          * @param string $exceptionClass
          * @return $this
        */
        public function pipeExceptionHandler(string $exceptionClass): self
        {
             $this->singleton(\Laventure\Contract\Debug\ExceptionHandler::class, $exceptionClass);

             return $this;
        }




        /**
         * @param Request $request
         * @param Response $response
         * @return void
        */
        public function terminate(Request $request, Response $response)
        {
              // show content
              $response->sendBody();

              // remove session flashes
              // $request->sessions->removeFlashes();

              // display debug information
        }
}