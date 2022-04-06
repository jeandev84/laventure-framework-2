<?php
namespace Laventure\Foundation\Http;


use Exception;
use Laventure\Component\Http\Request\Request;
use Laventure\Component\Http\Response\Response;
use Laventure\Foundation\Routing\Router;
use Laventure\Contract\Http\Kernel as HttpKernelContract;
use Laventure\Foundation\Application;
use Laventure\Foundation\Http\Middleware\SessionStartMiddleware;


/**
 * HTTP Kernel
 *
 * @Kernel
*/
class Kernel implements HttpKernelContract
{


    /**
     * @var Application
    */
    protected $app;




    /**
     * @var Router
    */
    protected $router;



    /**
     * @var array
    */
    private $middlewarePriority = [
         SessionStartMiddleware::class
    ];



    /**
     * @var array
    */
    protected $middlewares = [];




    /**
     * @var array
    */
    protected $routeMiddlewares = [];




    /**
     * Kernel constructor
     *
     * @param Application $app
     * @param Router $router
    */
    public function __construct(Application $app, Router $router)
    {
          $this->app    = $app;
          $this->router = $router;
    }




    /**
     * @inheritDoc
    */
    public function handle(Request $request): Response
    {
        try {

            $response = $this->dispatchRoute($request);

        } catch (Exception $e) {

            $response = $this->renderException($e);
        }


        // dispatch events

        // get response
        return $response;
    }



    /**
     * @param Request $request
     * @return Response
    */
    protected function dispatchRoute(Request $request): Response
    {
         return $this->router->middlewares($this->getPriorityMiddlewares())
                             ->routeMiddlewares($this->routeMiddlewares)
                             ->dispatch($request);
    }




    /**
     * @inheritDoc
    */
    public function terminate(Request $request, Response $response)
    {
          $this->app->terminate($request, $response);
    }



    /**
     * @param Exception $e
     * @return Response
    */
    protected function renderException(Exception $e): Response
    {
        return new Response($e->getMessage());
    }


    /**
     * @return object[]
    */
    private function getPriorityMiddlewares(): array
    {
        return array_merge($this->middlewarePriority, $this->middlewares);
    }
}