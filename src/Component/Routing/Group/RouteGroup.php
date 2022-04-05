<?php
namespace Laventure\Component\Routing\Group;


use Closure;
use Laventure\Component\Routing\Router;


/**
 * @RouteGroup
*/
class RouteGroup
{


      /**
        * @var Closure
      */
      protected $routes;





      /**
       * @var array
      */
      protected $attributes = [
          'prefix'      => '',
          'module'      => '',
          'name'        => '',
          'middlewares' => []
      ];




      /**
       * @var array
      */
      protected $middlewares = [];




      /**
       * RouteGroup constructor
       *
       * @param array $middlewares
      */
      public function __construct(array $middlewares = [])
      {
           if ($middlewares) {
               $this->withMiddlewares($middlewares);
           }
      }



      /**
       * @param array $middlewares
       * @return $this
      */
      public function withMiddlewares(array $middlewares): self
      {
           $this->middlewares = $middlewares;

           return $this;
      }





      /**
       * @param Closure $routes
       * @return void
      */
      public function callback(Closure $routes)
      {
            $this->routes = $routes;
      }




      /**
       * @param Router $router
       * @return void
      */
      public function callRoutes(Router $router)
      {
           if(is_callable($this->routes)) {
               ($this->routes)($router);
           }
      }





      /**
       * @param array $attributes
       * @return $this
      */
      public function withAttributes(array $attributes): self
      {
         if (! empty($attributes['middlewares'])) {
             $attributes['middlewares'] = $this->populateMiddlewares($attributes['middlewares']);
         }

         $this->attributes = array_merge($this->attributes, $attributes);

         return $this;
      }




      /**
       * @return string
      */
      public function getPrefix(): string
      {
         return $this->getAttribute("prefix", "");
      }




     /**
      * Get module
      *
      * @return string
     */
     public function getModule(): string
     {
          return $this->getAttribute("module", "");
     }



     /**
      * Get name
      *
      * @return string
     */
     public function getName(): string
     {
         return $this->getAttribute("name", "");
     }




     /**
      * @return array
     */
     public function getMiddlewares(): array
     {
         return $this->getAttribute("middlewares", []);
     }




    /**
     * @return array
    */
    public function getAttributes(): array
    {
        return $this->attributes;
    }





    /**
     * @param string $name
     * @param $default
     * @return mixed
    */
    public function getAttribute(string $name, $default)
    {
        return $this->attributes[$name] ?? $default;
    }




    /**
     * @return void
    */
    public function removeAttributes()
    {
        $this->attributes = [];
    }




    /**
     * @param string|array $middlewares
     * @return array
    */
    protected function populateMiddlewares($middlewares): array
    {
        $middlewares = (array) $middlewares;

        $routeMiddlewares = [];

        foreach ($middlewares as $name) {
            $routeMiddlewares[] = $this->middlewares[$name] ?? $name;
        }

        return $routeMiddlewares;
    }

}