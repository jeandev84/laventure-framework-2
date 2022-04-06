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
         foreach ($this->getParams() as $params) {

             list($methods, $path, $action, $name) = $params;

             $this->routes[] = $router->map($methods, $path, $action, $name);
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
                'GET',
                $this->makeRoutePath(),
                $this->makeRouteAction('list'),
                $this->makeRouteName('list')
            ],
            'show' => [
                'GET',
                $this->makeRoutePath('/{id}'),
                $this->makeRouteAction('show'),
                $this->makeRouteName('show')
            ],
            'create' => [
                'GET|POST',
                $this->makeRoutePath('/create'),
                $this->makeRouteAction('create'),
                $this->makeRouteName('create')
            ],
            'edit' => [
                'GET|POST',
                $this->makeRoutePath('/{id}/edit'),
                $this->makeRouteAction('edit'),
                $this->makeRouteName('edit')
            ],
            'destroy' => [
                'DELETE',
                $this->makeRoutePath('/delete/{id}'),
                $this->makeRouteAction('delete'),
                $this->makeRouteName('delete')
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

}