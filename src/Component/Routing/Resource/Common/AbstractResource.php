<?php
namespace Laventure\Component\Routing\Resource\Common;


use Laventure\Component\Routing\Collection\Route;
use Laventure\Component\Routing\Router;


/**
 * @AbstractResource
*/
abstract class AbstractResource
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
      * @param string $name
      * @param string $controller
     */
     public function __construct(string $name, string $controller)
     {
          $this->name = $name;
          $this->controller = $controller;
     }




     /**
      * Get resource name
      *
      * @return string
     */
     public function getName(): string
     {
         return $this->name;
     }




     /**
      * Get resource controller
      *
      * @return string
     */
     public function getController(): string
     {
          return $this->controller;
     }




     /**
      * Get resource routes
      *
      * @return Route[]
     */
     public function getRoutes(): array
     {
         return $this->routes;
     }




     
     /**
      * Map routes
      *
      * @param Router $router
      * @return void
     */
     public function mapRoutes(Router $router)
     {
         foreach ($this->getParams() as $params) {

             list($methods, $path, $action, $name) = $params;

             $this->routes[] = $router->map($methods, $this->path($path), $this->action($action), $this->name($name));

         }

     }




     /**
      * Generate route path.
      *
      * @param string $path
      * @return string
     */
     protected function path(string $path = ''): string
     {
          return trim($this->name, 's') . $path;
     }




     /**
      * Generate route action
      *
      * @param string $action
      * @return string
     */
     protected function action(string $action): string
     {
          return sprintf('%s@%s', $this->controller, $action);
     }




     /**
      * Generate route name
      *
      * @param string $name
      * @return string
     */
     protected function name(string $name): string
     {
         return sprintf('%s.%s', $this->name, $name);
     }






     /**
      * Get resource config params
      *
      * @return array
     */
     public function getParams(): array
     {
         return ['list', 'show', 'create', 'edit', 'destroy'];
     }




    /**
     * Get resource actions
     *
     * @return array
    */
    abstract public static function getActions(): array;

}