<?php
namespace Laventure\Foundation\Loader;


use Laventure\Component\Container\Container;
use Laventure\Component\FileSystem\FileSystem;
use Laventure\Component\Routing\Router;
use Laventure\Foundation\Loader;


/**
 * @RouteLoader
*/
class RouteLoader extends Loader
{

     /**
      * @var Router
     */
     protected $router;




     /**
      * @var string
     */
     protected $controllerPath;




     /**
      * @var string
     */
     protected $webRoutePath;




     /**
      * @var string
     */
     protected $apiRoutePath;





     /**
      * @param Container $app
      * @param Router $router
     */
     public function __construct(Container $app, Router $router)
     {
         parent::__construct($app);
         $this->router = $router;
     }




     /**
      * @return Router
     */
     public function getRouter(): Router
     {
          return $this->router;
     }





     /**
      * @param string $path
      * @return $this
     */
     public function setWebRoutePath(string $path): self
     {
           $this->webRoutePath = $path;

           return $this;
     }



     /**
      * @return string
     */
     public function getWebRoutePath(): string
     {
          return $this->webRoutePath;
     }



     /**
      * @param string $path
      * @return $this
     */
     public function setApiRoutePath(string $path): self
     {
          $this->apiRoutePath = $path;

          return $this;
     }




     /**
      * @return string
     */
     public function getApiRoutePath(): string
     {
         return $this->apiRoutePath;
     }





     /**
       * @param string|null $module
       * @return string
     */
     public function loadControllerNamespace(string $module = null): string
     {
          return $this->router->getNamespace($module);
     }



     /**
      * @param string $controllerClass
      * @return string
     */
     public function loadController(string $controllerClass): string
     {
           return $this->router->getController($controllerClass);
     }




     /**
      * @param FileSystem $fileSystem
      * @return void
     */
     public function loadPaths(FileSystem $fileSystem)
     {
         parent::loadPaths($fileSystem);

         $this->app->bind('_routes',  $this->router->getRoutes());
     }



     /**
      * @param string $controllerPath
      * @return $this
     */
     public function setControllerPath(string $controllerPath): self
     {
           $this->controllerPath = $controllerPath;

           return $this;
     }



     /**
      * @param $controllerName
      * @return string
     */
     public function generateControllerPath($controllerName): string
     {
         return $this->makePath($this->controllerPath, $controllerName);
     }
}