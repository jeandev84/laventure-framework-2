<?php
namespace Laventure\Foundation\Providers;


use Laventure\Component\Container\ServiceProvider\ServiceProvider;
use Laventure\Component\Routing\Router;
use Laventure\Component\Routing\RouterInterface;
use Laventure\Foundation\Loaders\RouteLoader;
use Laventure\Foundation\Routing\DefaultController;
use Laventure\Foundation\Routing\LaventureRouter;


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

               $router = new LaventureRouter($this->app, $this->app['middleware']);
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