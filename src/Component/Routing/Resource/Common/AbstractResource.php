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
      * @param string|null $name
      * @param string|null $controller
     */
     public function __construct(string $name = null, string $controller = null)
     {
          if ($name) {
              $this->name($name);
          }

          if ($controller) {
              $this->controller($controller);
          }
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
     protected function makeRoutePath(string $path = ''): string
     {
          return trim($this->name, 's') . $path;
     }




     /**
      * Generate route action
      *
      * @param string $action
      * @return string
     */
     protected function makeRouteAction(string $action): string
     {
          return sprintf('%s@%s', $this->getController(), $action);
     }




     /**
      * Generate route name
      *
      * @param string $name
      * @return string
     */
     protected function makeRouteName(string $name): string
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
                'path'     => $this->makeRoutePath(),
                'action'   => $this->makeRouteAction('list'),
                'name'     => $this->makeRouteName('list')
            ],
            'show' => [
                'methods'  => 'GET',
                'path'     => $this->makeRoutePath('/{id}'),
                'action'   => $this->makeRouteAction('show'),
                'name'     => $this->makeRouteName('show')
            ],
            'create' => [
                'methods'  => 'GET|POST',
                'path'     => $this->makeRoutePath('/create'),
                'action'   => $this->makeRouteAction('create'),
                'name'     => $this->makeRouteName('create')
            ],
            'edit' => [
                'methods'  => 'GET|POST',
                'path'     => $this->makeRoutePath('/{id}/edit'),
                'action'   => $this->makeRouteAction('edit'),
                'name'     => $this->makeRouteName('edit')
            ],
            'destroy' => [
                'methods'  => 'DELETE',
                'path'     => $this->makeRoutePath('/destroy/{id}'),
                'action'   => $this->makeRouteAction('destroy'),
                'name'     => $this->makeRouteName('destroy')
            ]
        ];
    }





    /**
     * @return array
    */
    public function getFilteredRouteParams(): array
    {
        $filtered = [];

        foreach ($this->getParams() as $index => $params) {
            $filtered[$index] = [$params[0], $params[1], $params[3]];
        }

        return $filtered;
    }



    public function makeViewPath()
    {

    }

}