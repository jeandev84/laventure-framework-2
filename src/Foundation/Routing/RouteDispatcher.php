<?php
namespace Laventure\Foundation\Routing;


use Laventure\Component\Container\Container;
use Laventure\Component\Http\Response\JsonResponse;
use Laventure\Component\Http\Response\Response;
use Laventure\Component\Routing\Collection\Route;
use Laventure\Component\Routing\Dispatcher\RouteDispatcherInterface;


/**
 * @RouteDispatcher
*/
class RouteDispatcher implements RouteDispatcherInterface
{


    /**
     * @var Container
    */
    protected $app;



    /**
     * @param Container $app
    */
    public function __construct(Container $app)
    {
          $this->app = $app;
    }




    /**
     * @inheritDoc
    */
    public function dispatchRoute(Route $route)
    {
        return $this->app->call($route->getCallback(), $route->getMatches());
    }
}