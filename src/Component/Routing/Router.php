<?php
namespace Laventure\Component\Routing;


use Closure;
use Laventure\Component\Routing\Collection\Route;
use Laventure\Component\Routing\Collection\RouteCollection;
use Laventure\Component\Routing\Dispatcher\RouteDispatcher;
use Laventure\Component\Routing\Dispatcher\RouteDispatcherInterface;
use Laventure\Component\Routing\Exception\NotFoundException;
use Laventure\Component\Routing\Group\RouteGroup;
use Laventure\Component\Routing\Resource\ApiResource;
use Laventure\Component\Routing\Resource\Contract\ApiResourceInterface;
use Laventure\Component\Routing\Resource\Contract\WebResourceInterface;
use Laventure\Component\Routing\Utils\Factory;
use Laventure\Component\Routing\Utils\Resolver;


/**
 * @Router
*/
class Router implements RouterInterface
{



    /**
     * Domain
     *
     * @var string
    */
    protected $domain = '';





    /**
     * Controller namespace
     *
     * @var string
    */
    protected $namespace;




    /**
     * Route collection
     *
     * @var RouteCollection
    */
    protected $routes;





    /**
     * Current route
     *
     * @var Route
    */
    protected $route;




    /**
     * Route factory
     *
     * @var Factory
    */
    protected $factory;





    /**
     * Route dispatcher
     *
     * @var RouteDispatcherInterface
    */
    protected $dispatcher;





    /**
     * @var Resolver
    */
    protected $resolver;





    /**
     * Route middlewares
     *
     * @var array
    */
    protected $routeMiddlewares = [];




    /**
     * Collection route groups
     *
     * @var RouteGroup[]
    */
    protected $groupRoutes = [];





    /**
     * Collection web resources
     *
     * @var WebResourceInterface[]
    */
    protected $resources = [];





    /**
     * Collection api resources
     *
     * @var ApiResource[]
    */
    protected $apiResources = [];





    /**
     * Route patterns
     *
     * @var array
    */
    protected $patterns = [
        'id'    => '\d+',
        'lang'  => '\w+',
    ];





    /**
     * Router constructor
     *
     * @param RouteDispatcherInterface|null $dispatcher
    */
    public function __construct(RouteDispatcherInterface $dispatcher = null)
    {
         $this->factory        = new Factory();
         $this->routes         = new RouteCollection();
         $this->resolver       = new Resolver();
         $this->dispatcher     = $dispatcher ?? new RouteDispatcher();
    }




    /**
     * @param array $middlewares
     * @return $this
    */
    public function routeMiddlewares(array $middlewares): self
    {
         $this->routeMiddlewares = $middlewares;

         return $this;
    }





    /**
     * @return array
    */
    public function getRouteMiddlewares(): array
    {
        return $this->routeMiddlewares;
    }





    /**
     * Add route
     *
     * @param Route $route
     * @return Route
    */
    public function addRoute(Route $route): Route
    {
        $middlewares = $this->getGroupMiddlewares();

        $route->domain($this->getDomain())
              ->where($this->patterns)
              ->middlewares($middlewares);

        return $this->routes->addRoute($route);
    }






    /**
     * Add routes
     *
     * @param array $routes
     * @return void
    */
    public function addRoutes(array $routes)
    {
        foreach ($routes as $route) {
            $this->addRoute($route);
        }
    }




    /**
     * Add resource
     *
     * @param WebResourceInterface $resource
     * @return $this
    */
    public function addResource(WebResourceInterface $resource): self
    {
        $resource->mapRoutes($this);

        $this->resources[$resource->getName()] = $resource;

        return $this;
    }




    /**
     * @param RouteGroup $group
     * @return $this
    */
    public function addGroup(RouteGroup $group): self
    {
        $this->resolver->setGroup($group);

        $group->callRoutes($this);

        $this->groupRoutes[] = $group;

        $group->removeAttributes();

        return $this;
    }





    /**
     * @param string $name
     * @return bool
     */
    public function hasResource(string $name): bool
    {
        return array_key_exists($name, $this->resources);
    }




    /**
     * @param string $name
     * @return WebResourceInterface|null
     */
    public function getResource(string $name): ?WebResourceInterface
    {
        return $this->resources[$name] ?? null;
    }






    /**
     * @param ApiResourceInterface $resource
     * @return $this
     */
    public function addResourceAPI(ApiResourceInterface $resource): self
    {
        $resource->mapRoutes($this);

        $this->apiResources[$resource->getName()] = $resource;

        return $this;
    }





    /**
     * @param string $name
     * @return bool
     */
    public function hasResourceAPI(string $name): bool
    {
        return array_key_exists($name, $this->apiResources);
    }




    /**
     * @param string $name
     * @return ApiResource|null
     */
    public function getResourceAPI(string $name): ?ApiResource
    {
        return $this->apiResources[$name] ?? null;
    }






    /**
     * @param $methods
     * @param string $path
     * @param $action
     * @param array $options
     * @return Route
     */
    public function makeRoute($methods, string $path, $action, array $options = []): Route
    {
        return $this->factory->createRoute($methods, $path, $action, $options);
    }





    /**
     * Map route
     *
     * @param string|array $methods
     * @param string $path
     * @param mixed $action
     * @param string|null $name
     * @return Route
     */
    public function map($methods, string $path, $action, string $name = null): Route
    {
        $methods = $this->resolveMethods($methods);
        $path    = $this->resolvePath($path);
        $action  = $this->resolveAction($action);

        $route = $this->makeRoute($methods, $path, $action, $this->getOptions());

        $route->target($this->resolveTarget($action));

        if ($name) {
            $route->name($name);
        }

        return $this->addRoute($route);
    }




    /**
     * Determine if matched route
     *
     * @param string $requestMethod
     * @param string $requestPath
     * @return false|Route
     */
    public function match(string $requestMethod, string $requestPath)
    {
        foreach ($this->getRoutes() as $route) {
            if ($route->match($requestMethod, $requestPath)) {
                return $this->setRoute($route);
            }
        }

        return false;
    }




    /**
     * Determine if exist route given name
     *
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return $this->routes->has($name);
    }





    /**
     * @param string $name
     * @return void
     */
    public function remove(string $name)
    {
        $this->routes->remove($name);
    }





    /**
     * @inheritDoc
     */
    public function generate(string $name, array $parameters = [])
    {
        if (! $route = $this->routes->getRoute($name)) {
            return false;
        }

        return $route->generatePath($parameters);
    }




    /**
     * @inheritDoc
     *
     * @return Route[]
     */
    public function getRoutes(): array
    {
        return $this->routes->getRoutes();
    }




    /**
     * @return bool
     */
    public function hasRoutes(): bool
    {
        return empty($this->getRoutes());
    }



    /**
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
    }




    /**
     * @return RouteCollection
     */
    public function getCollection(): RouteCollection
    {
        return $this->routes;
    }




    /**
     * @inheritDoc
     */
    public function getRoute()
    {
        return $this->route;
    }




    /**
     * @param Route $route
     * @return Route
     */
    public function setRoute(Route $route): Route
    {
        $this->route = $route;

        return $route;
    }




    /**
     * Set route domain
     *
     * @param string $domain
     * @return $this
     */
    public function domain(string $domain): self
    {
        $this->domain = $domain;

        return $this;
    }




    /**
     * Set global patterns
     *
     * @param array $patterns
     * @return void
     */
    public function patterns(array $patterns)
    {
        foreach ($patterns as $name => $regex) {
            $this->pattern($name, $regex);
        }
    }




    /**
     * Set global pattern
     *
     * @param string $name
     * @param string $regex
     * @return $this
     */
    public function pattern(string $name, string $regex): self
    {
        $this->patterns[$name] = $regex;

        return $this;
    }



    /**
     * @param string $namespace
     * @return $this
     */
    public function namespace(string $namespace): self
    {
        $this->resolver->namespace($namespace);

        return $this;
    }





    /**
     * @param array $attributes
     * @return $this
     */
    public function prefixes(array $attributes): self
    {
        $this->resolver->withAttributes($attributes);

        return $this;
    }




    /**
     * @param string $prefix
     * @return $this
     */
    public function prefix(string $prefix): self
    {
        $this->prefixes(compact('prefix'));

        return $this;
    }




    /**
     * @param string $module
     * @return $this
     */
    public function module(string $module): self
    {
        $this->prefixes(compact('module'));

        return $this;
    }




    /**
     * @param string $name
     * @return $this
     */
    public function name(string $name): self
    {
        return $this->prefixes(compact('name'));
    }




    /**
     * @param $middlewares
     * @return $this
     */
    public function middleware($middlewares): self
    {
        return $this->prefixes(compact('middlewares'));
    }






    /**
     * @return array
     */
    public function getGroupMiddlewares(): array
    {
        return $this->resolver->getGroup()->getMiddlewares();
    }





    /**
     * @return string
     */
    protected function resolvedDomain(): string
    {
        return trim($this->domain, '\\/');
    }





    /**
     * @param string|null $module
     * @return string
     */
    public function getNamespace(string $module = null): string
    {
        return $this->resolver->getNamespace($module);
    }




    /**
     * @return string
     */
    public function getControllerNamespace(): string
    {
        return $this->resolver->getControllerNamespace();
    }





    /**
     * @param string $class
     * @return string
     */
    public function getController(string $class): string
    {
        return $this->resolver->getController($class);
    }




    /**
     * Resolve methods
     *
     * @param $methods
     * @return array
     */
    protected function resolveMethods($methods): array
    {
        return $this->resolver->resolveMethods($methods);
    }





    /**
     * Resolve path
     *
     * @param $path
     * @return string
     */
    protected function resolvePath($path): string
    {
        return $this->resolver->resolvePath($path);
    }





    /**
     * @param mixed $action
     * @return mixed
     */
    protected function resolveAction($action)
    {
        return $this->resolver->resolveAction($action);
    }





    /**
     * @param $action
     * @return string
     */
    protected function resolveTarget($action): string
    {
        return $this->resolver->resolveTarget($action);
    }




    /**
     * @return array
    */
    protected function getOptions(): array
    {
        return $this->resolver->getRouteOptions();
    }





    /**
     * @param Route $route
     * @return false|mixed
    */
    public function dispatchRoute(Route $route)
    {
        return $this->dispatcher->dispatchRoute($route);
    }


    /**
     * Call route
     *
     * @param string $requestMethod
     * @param string $requestPath
     * @return void
     * @throws NotFoundException
    */
    public function run(string $requestMethod, string $requestPath)
    {
        // check match route
        if (! $route = $this->match($requestMethod, $requestPath)) {
            throw new NotFoundException("route '{$requestPath}' not found");
        }

        return $this->dispatchRoute($route);
    }





    /**
     * Add route will be called by method GET
     *
     *
     * @param string $path
     * @param mixed $action
     * @param string|null $name
     * @return Route
    */
    public function get(string $path, $action, string $name = null): Route
    {
        return $this->map('GET', $path, $action, $name);
    }





    /**
     * Add route will be called by method POST
     *
     *
     * @param string $path
     * @param mixed $action
     * @param string|null $name
     * @return Route
     */
    public function post(string $path, $action, string $name = null): Route
    {
        return $this->map('POST', $path, $action, $name);
    }






    /**
     * Add route will be called by method PUT
     *
     * @param string $path
     * @param mixed $action
     * @param string|null $name
     * @return Route
     */
    public function put(string $path, $action, string $name = null): Route
    {
        return $this->map('PUT', $path, $action, $name);
    }





    /**
     * Add route will be called by method PATCH
     *
     * @param string $path
     * @param mixed $action
     * @param string|null $name
     * @return Route
     */
    public function patch(string $path, $action, string $name = null): Route
    {
        return $this->map('PATCH', $path, $action, $name);
    }





    /**
     * Add route will be called by method DELETE
     *
     * @param string $path
     * @param mixed $action
     * @param string|null $name
     * @return Route
     */
    public function delete(string $path, $action, string $name = null): Route
    {
        return $this->map('DELETE', $path, $action, $name);
    }





    /**
     * Add route will be called by method DELETE
     *
     * @param string $path
     * @param mixed $action
     * @param string|null $name
     * @return Route
     */
    public function options(string $path, $action, string $name = null): Route
    {
        return $this->map('OPTIONS', $path, $action, $name);
    }





    /**
     * Add route will be called by method each method [ GET, POST, PUT, DELETE, PATCH ]
     *
     * @param string $path
     * @param mixed $action
     * @param string|null $name
     * @return Route
     */
    public function any(string $path, $action, string $name = null): Route
    {
        return $this->map('GET|POST|PUT|DELETE|PATCH', $path, $action, $name);
    }






    /**
     * Add routes group
     *
     * @param Closure $routes
     * @param array $attributes
     * @return $this
     */
    public function group(Closure $routes, array $attributes = []): self
    {
        $group = $this->factory->createRouteGroup($this->routeMiddlewares, $routes);

        if ($attributes) {
            $group->withAttributes($attributes);
        }

        return $this->addGroup($group);
    }



    /**
     * @param Closure|null $closure
     * @param array $attributes
     * @return $this
     */
    public function api(Closure $closure = null, array $attributes = []): self
    {
        $attributes = $this->getAttributesAPI($attributes);

        if (! $closure) {
            $this->prefixes($attributes);
            return $this;
        }

        return $this->group($closure, $attributes);
    }




    /**
     * @param string $name
     * @param string $controller
     * @return $this
     */
    public function resource(string $name, string $controller): self
    {
        $resource = $this->factory->createResource($name, $controller);

        return $this->addResource($resource);
    }




    /**
     * @param string $name
     * @param string $controller
     * @return $this
     */
    public function resourceAPI(string $name, string $controller): self
    {
        $resource = $this->factory->createResourceAPI($name, $controller);

        return $this->addResourceAPI($resource);
    }



    /**
     * Generate full route path, used for other domain
     *
     * @param string $name
     * @param array $parameters
     * @return string
     */
    public function url(string $name, array $parameters = []): string
    {
        return $this->resolvedDomain() . $this->generate($name, $parameters);
    }




    /**
     * @return string[]
     */
    protected function getAttributesAPI(array $attributes = []): array
    {
        return array_merge($this->getDefaultAttributesAPI(), $attributes);
    }




    /**
     * @return array
     */
    protected function getDefaultAttributesAPI(): array
    {
        return $this->resolver->getAttributeAPI();
    }

}