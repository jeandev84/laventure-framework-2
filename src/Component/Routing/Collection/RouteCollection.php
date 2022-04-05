<?php
namespace Laventure\Component\Routing\Collection;


use Exception;
use RuntimeException;


/**
 * @RouteCollection
*/
class RouteCollection implements RouteCollectionInterface
{


    /**
     * Route collection
     *
     * @var Route[]
    */
    protected $routes = [];




    /**
     * Routes by method
     *
     * @var Route[]
    */
    protected $methods = [];




    /**
     * Routes by name
     *
     * @var Route[]
    */
    protected $names = [];




    /**
     * Routes by controller
     *
     * @var Route[]
    */
    protected $controllers = [];





    /**
     * RouteCollection constructor
     *
     * @param Route[] $routes
    */
    public function __construct(array $routes = [])
    {
         if ($routes) {
             $this->addRoutes($routes);
         }
    }




    /**
     * @param Route $route
     * @return Route
    */
    public function addRoute(Route $route): Route
    {
        $this->collectRoutes($route);

        $this->refreshNames();

        return $route;
    }




    /**
     * @param Route[] $routes
     * @return void
    */
    public function addRoutes(array $routes)
    {
         foreach ($routes as $route) {
             $this->addRoute($route);
         }
    }




    /**
     * @param string $name
     * @param Route $route
     * @return Route
    */
    public function add(string $name, Route $route): Route
    {
         $route->name($name);

         return $this->addRoute($route);
    }




    /**
     * Add route to collections
     *
     * @param Route $route
     * @return $this
    */
    public function collectRoutes(Route $route): self
    {
        $methods = $route->getMethodsToString();

        $this->methods[$methods][$route->getPath()] = $route;

        if ($controller = $route->getControllerName()) {
            $this->controllers[$controller][] = $route;
        }

        $this->routes[$route->getPath()] = $route;

        return $this;
    }




    /**
     * Determine if route name exists in named routes
     *
     * @param string $name
     * @return bool
    */
    public function has(string $name): bool
    {
         return array_key_exists($name, $this->names);
    }




    /**
     * Get named route
     *
     * @param string $name
     * @return Route|null
    */
    public function getRoute(string $name): ?Route
    {
        return $this->names[$name] ?? null;
    }




    /**
     * Get all routes
     *
     * @return Route[]
    */
    public function getRoutes(): array
    {
        return $this->routes;
    }




    /**
     * Get routes by methods
     *
     * @return Route[]
    */
    public function getRoutesByMethod(): array
    {
        return $this->methods;
    }




    /**
     * Get routes by name
     *
     * @return Route[]
    */
    public function getRoutesByName(): array
    {
        return $this->names;
    }




    /**
     * @param string $name
     * @return void
    */
    public function remove(string $name)
    {
         if ($this->has($name)) {

             $route = $this->getRoute($name);

             if ($controller = $route->getControllerName()) {
                 unset($this->controllers[$controller]);
             }

             $key = array_search($route, $this->routes);
             unset($this->methods[$route->getMethodsToString()][$route->getPath()]);
             unset($this->routes[$key]);
             unset($this->names[$name]);
         }
    }



    /**
     * @return int
    */
    public function count(): int
    {
        return count($this->routes);
    }




    /**
     * Throws exception.
     *
     * @param Exception $e
     * @return mixed
    */
    protected function abortIf(Exception $e)
    {
        return (function () use ($e) {
            throw $e;
        })();
    }




    /**
     * @param Route $route
     * @return RuntimeException
    */
    private function uniqueNameException(Route $route): RuntimeException
    {
        return new RuntimeException("Cannot redeclare name '({$route->getName()})' for route ({$route->getPath()})");
    }




    /**
     * Refresh named routes
     *
     * @return void
    */
    private function refreshNames()
    {
        $this->names = [];

        foreach ($this->routes as $route) {
            if ($name = $route->getName()) {
                if ($this->has($name)) {
                    $this->abortIf($this->uniqueNameException($route));
                }

                $this->names[$name] = $route;
            }
        }
    }

}