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
      * @var string
     */
     protected $path;




     /**
      * @var Route[]
     */
     protected $routes = [];




     /**
      * @param string|null $name
      * @param string|null $controller
     */
     public function __construct(string $name = null, string $controller = null, string $controllerSuffix = null)
     {
          if ($name) {
              $this->name($name);
          }

          if ($controller) {
              $this->controller($controller);
          }


          $this->path = $this->preparePath($controllerSuffix, $controller);
     }






     /**
      * @param string $name
      * @return $this
     */
     public function name(string $name): self
     {
          $this->name = $name;

          return $this;
     }




     /**
      * @param string $controller
      * @return $this
     */
     public function controller(string $controller): self
     {
          $this->controller = $controller;

          return $this;
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
         foreach ($this->getParams() as $param) {

             $this->routes[] = $router->map(
                 $param['methods'],
                 $param['path'],
                 $param['action'],
                 $param['name']
             );
         }

     }




     /**
      * Generate route path.
      *
      * @param string $path
      * @return string
     */
     protected function generatePath(string $path = ''): string
     {
          return trim($this->path, 's') . $path;
     }




     /**
      * Generate route action
      *
      * @param string $action
      * @return string
     */
     protected function generateAction(string $action): string
     {
          return sprintf('%s@%s', $this->getController(), $action);
     }




     /**
      * Generate route name
      *
      * @param string $name
      * @return string
     */
     protected function generateName(string $name): string
     {
         return sprintf('%s.%s', $this->getName(), $name);
     }





     /**
      * Get resource config params
      *
      * @return array
     */
     public function getParams(): array
     {
        return [
            'list' => [
                'methods'  => 'GET',
                'path'     => $this->generatePath(),
                'action'   => $this->generateAction('list'),
                'name'     => $this->generateName('list')
            ],
            'show' => [
                'methods'  => 'GET',
                'path'     => $this->generatePath('/{id}'),
                'action'   => $this->generateAction('show'),
                'name'     => $this->generateName('show')
            ],
            'create' => [
                'methods'  => 'GET|POST',
                'path'     => $this->generatePath('/create'),
                'action'   => $this->generateAction('create'),
                'name'     => $this->generateName('create')
            ],
            'edit' => [
                'methods'  => 'GET|POST',
                'path'     => $this->generatePath('/{id}/edit'),
                'action'   => $this->generateAction('edit'),
                'name'     => $this->generateName('edit')
            ],
            'destroy' => [
                'methods'  => 'DELETE',
                'path'     => $this->generatePath('/destroy/{id}'),
                'action'   => $this->generateAction('destroy'),
                'name'     => $this->generateName('destroy')
            ]
        ];
    }




    /**
     * @param string $controllerSuffix
     * @param string $controller
     * @return string
    */
    protected function preparePath(string $controllerSuffix, string $controller): string
    {
         return strtolower(str_replace($controllerSuffix, '', $controller));
    }
}