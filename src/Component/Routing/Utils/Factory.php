<?php
namespace Laventure\Component\Routing\Utils;



use Closure;
use Laventure\Component\Routing\Collection\Route;
use Laventure\Component\Routing\Group\RouteGroup;
use Laventure\Component\Routing\Resource\ApiResource;
use Laventure\Component\Routing\Resource\Contract\ApiResourceInterface;
use Laventure\Component\Routing\Resource\Contract\WebResourceInterface;
use Laventure\Component\Routing\Resource\WebResource;


/**
 * @Factory
*/
class Factory
{


    /**
     * @var string
    */
    protected $controllerSuffix;



    /**
     * @param string|null $controllerSuffix
    */
    public function __construct(string $controllerSuffix = '')
    {
         if ($controllerSuffix) {
             $this->controllerSuffix = $controllerSuffix;
         }
    }



    /**
     * @param $methods
     * @param string $path
     * @param $action
     * @param array $options
     * @return Route
    */
    public function createRoute($methods, string $path, $action, array $options = []): Route
    {
         return new Route($methods, $path, $action, $options);
    }




    /**
     * @param array $middlewares
     * @param Closure|null $routes
     * @return RouteGroup
    */
    public function createRouteGroup(array $middlewares = [], Closure $routes = null): RouteGroup
    {
         $routeGroup = new RouteGroup($middlewares);

         if ($routes) {
             $routeGroup->callback($routes);
         }

         return $routeGroup;
    }



    /**
     * @param string $name
     * @param string $controller
     * @return WebResourceInterface
    */
    public function createResource(string $name, string $controller): WebResourceInterface
    {
          return new WebResource($name, $controller, $this->controllerSuffix);
    }




    /**
     * @param string $name
     * @param string $controller
     * @return ApiResourceInterface
    */
    public function createResourceAPI(string $name, string $controller): ApiResourceInterface
    {
        return new ApiResource($name, $controller, $this->controllerSuffix);
    }
}