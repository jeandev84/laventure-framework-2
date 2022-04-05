<?php
namespace Laventure\Component\Routing\Dispatcher;


use Laventure\Component\Routing\Collection\Route;


/**
 * @RouteDispatcher
*/
class RouteDispatcher implements RouteDispatcherInterface
{

    /**
     * @inheritDoc
    */
    public function dispatchRoute(Route $route)
    {
        return (function () use ($route) {

            if (! $route->callable()) {

                $controller = $route->getController();
                $action     = $route->getAction();

                $route->callback([new $controller, $action]);
            }

            return $route->call();

        })();
    }
}