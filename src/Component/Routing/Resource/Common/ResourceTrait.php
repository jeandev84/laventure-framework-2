<?php
namespace Laventure\Component\Routing\Resource\Common;


use Laventure\Component\Routing\Collection\Route;
use Laventure\Component\Routing\Router;


/**
 * @Resource
*/
trait ResourceTrait
{


     /**
      * @var string
     */
     protected $name;



     /**
      * @var string
     */
     protected $controller;




     /**
      * @var Route[]
     */
     protected $routes = [];




     /**
      * @var array
     */
     protected $actions = [];



     /**
      * @param string $name
      * @param string $controller
     */
     public function __construct(string $name, string $controller)
     {
          $this->name = $name;
          $this->controller = $controller;
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
     public function getController(): string
     {
          return $this->controller;
     }



     /**
      * @return Route[]
     */
     public function getRoutes(): array
     {
         return $this->routes;
     }




     /**
      * @return array
     */
     public function getActions(): array
     {
         return $this->actions;
     }


     
     /**
      * @param Router $router
      * @return void
     */
     public function mapRoutes(Router $router)
     {
           \trigger_error(__METHOD__. " must be implements.");
     }




     /**
      * @param Router $router
      * @param array $params
      * @return $this
     */
     protected function map(Router $router, array $params): self
     {
         list($methods, $path, $action, $name) = $params;

         $route = $router->map($methods, $this->path($path), $action = $this->action($action), $this->name($name));

         $this->routes[]  = $route;
         $this->actions[] = $action;
         
         return $this;
     }





     /**
      * @param string $path
      * @return string
     */
     protected function path(string $path = ''): string
     {
         return trim($this->name, 's') . $path;
     }



     /**
      * @param string $action
      * @return string
     */
     protected function action(string $action): string
     {
         return sprintf('%s@%s', $this->controller, $action);
     }




     /**
      * @param string $name
      * @return string
     */
     protected function name(string $name): string
     {
        return $this->name . '.'. $name;
     }
}