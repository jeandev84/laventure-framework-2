<?php
namespace Laventure\Foundation\Facade\Routing;


use Laventure\Component\Container\Facade\Facade;



/**
 * @Route
*/
class Route extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'router';
    }
}