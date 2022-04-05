<?php
namespace Laventure\Foundation\Providers;


use Laventure\Component\Container\ServiceProvider\ServiceProvider;
use Laventure\Component\Http\Middleware\Middleware;
use Laventure\Component\Routing\RouterInterface;
use Laventure\Foundation\Loaders\RouteLoader;
use Laventure\Foundation\Routing\DefaultController;
use Laventure\Foundation\Routing\Router;


/**
 * @RouteServiceProvider
*/
class RouteServiceProvider extends ServiceProvider
{


    /**
     * @var array
    */
    protected $provides = [
        Router::class => ['router', RouterInterface::class]
    ];



    /**
     * @var string[]
    */
    private $routePaths = [
        '/config/routes/web.php',
        '/config/routes/api.php'
    ];


    /**
     * @inheritDoc
    */
    public function register()
    {
         $this->app->singleton(Router::class, function () {

               $router = new Router($this->app, new Middleware());
               $router->namespace('App\\Http\\Controller');
               $router->get('/', [DefaultController::class, 'index'], 'default');
               return $router;
         });

    }



    /**
     * @return void
    */
    public function terminate()
    {
        $loader = new RouteLoader($this->app, $this->app[Router::class]);

        $loader->setControllerPath('app/Http/Controller')
               ->setWebRoutePath($this->routePaths[0])
               ->setApiRoutePath($this->routePaths[1])
               ->setLoadPaths($this->routePaths);

        $loader->loadPaths($this->app['@fs']);

        $this->app->instance(RouteLoader::class, $loader);
    }
}