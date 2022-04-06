<?php
namespace Laventure\Foundation\Routing;


use Laventure\Component\Container\Container;
use Laventure\Component\Http\Middleware\Middleware;
use Laventure\Component\Http\Request\Request;
use Laventure\Component\Http\Response\JsonResponse;
use Laventure\Component\Http\Response\Response;
use Laventure\Component\Routing\Collection\Route;
use Laventure\Component\Routing\Exception\NotFoundException;
use Laventure\Component\Routing\Router;



/**
 * @LaventureRouter
*/
class LaventureRouter extends Router
{

       /**
        * @var Container
       */
       protected $app;




       /**
        * @var Middleware
       */
       protected $middleware;





       /**
        * @var string[]
       */
       protected $middlewares = [];





       /**
        * Router constructor.
        *
        * @param Container $app
        * @param Middleware $middleware
       */
       public function __construct(Container $app, Middleware $middleware)
       {
            parent::__construct(new RouteDispatcher($app));
            $this->app        = $app;
            $this->middleware = $middleware;
       }




       /**
        * Add priority middlewares
        *
        * @param array $middlewares
        * @return $this
       */
       public function middlewares(array $middlewares): self
       {
            $this->middlewares = $middlewares;

            return $this;
       }





       /**
        * @param Request $request
        * @return Response
       */
       public function dispatch(Request $request): Response
       {
            $this->app->instance(Request::class, $request);

            return  (function () use ($request) {

                 $response =  $this->handle($request);
                 $response->setProtocolVersion($request->getProtocolVersion());

                 return $response;

            })();
       }




       /**
        * @param Request $request
        * @return Response
        * @throws NotFoundException
       */
       public function handle(Request $request): Response
       {
           // check match route
           if (! $route = $this->match($request->getMethod(), $requestPath = $request->getRequestUri())) {
               throw new NotFoundException("route '{$requestPath}' not found");
           }


           // set request attributes
           $request->setAttributes([
               '_route.name'    => $route->getName(),
               '_route.action'  => $route->getTarget(),
               '_route.params'  => $route->getMatches()
           ]);


           // bind current route
           $this->app->instance('_currentRoute', $route);


           // call middleware
           $this->middleware->addMiddlewares($this->resolvedMiddlewares($route));
           $this->middleware->handle($request);


           // call action
           if (! $route->callable()) {

               $controller  = $route->getController();
               $action      = $route->getAction();

               return $this->response(
                   $this->app->call($controller, $route->getMatches(), $action)
               );
           }

           return $this->response(
               $this->dispatcher->dispatchRoute($route)
           );
       }




       /**
        * @param Route $route
        * @return array
       */
       protected function resolvedMiddlewares(Route $route): array
       {
            $resolved = [];

            $middlewares = array_merge($this->middlewares, $route->getMiddlewares());

            /* $middlewares = array_merge($route->getMiddlewares(), $this->middlewares); */

            foreach ($middlewares as $middleware) {
                if (\is_string($middleware)) {
                    $middleware = $this->app->get($middleware);
                }

                if (is_object($middleware)) {
                    $resolved[] = $middleware;
                }
            }

            return $resolved;
       }




       /**
        * Get Response
        *
        * @param $response
        * @return JsonResponse|Response
      */
      protected function response($response = null)
      {
          if ($response instanceof Response) {
             return $response;
          }elseif (is_array($response)) {
             return new JsonResponse($response, 200);
          }

          return new Response($response, 200);
      }

}