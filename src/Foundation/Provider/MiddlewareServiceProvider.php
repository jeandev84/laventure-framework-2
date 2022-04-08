<?php
namespace Laventure\Foundation\Provider;


use Laventure\Component\Container\ServiceProvider\ServiceProvider;
use Laventure\Component\Http\Middleware\Middleware;


/**
 * @MiddlewareServiceProvider
*/
class MiddlewareServiceProvider extends ServiceProvider
{


    /**
     * @var array
    */
    protected $provides = [
        Middleware::class => ['middleware']
    ];




    /**
     * @inheritDoc
    */
    public function register()
    {
         $this->app->singleton(Middleware::class, function () {
             return new Middleware();
         });
    }
}