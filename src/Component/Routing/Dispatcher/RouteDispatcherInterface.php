<?php
namespace Laventure\Component\Routing\Dispatcher;


use Laventure\Component\Routing\Collection\Route;


/**
 * @RouteDispatcherInterface
*/
interface RouteDispatcherInterface
{
    /**
     * Dispatch route used for simple application
     * if we don't need to parse dependencies classes to the controller or method.
     *
     * @return mixed
    */
    public function dispatchRoute(Route $route);
}