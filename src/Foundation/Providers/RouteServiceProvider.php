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
     * @inheritDoc
    */
    public function register()
    {
         $this->app->singleton(Router::class, function () {

               $router = new Router($this->app, new Middleware());
               $router->namespace($this->getControllerNamespace());
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

        $loader->setControllerPath($this->getControllerPath())
               ->setWebRoutePath($this->getWebRoutePath())
               ->setApiRoutePath($this->getApiRoutePath())
               ->setLoadPaths($this->getRoutePaths());

        $loader->loadPaths($this->app['@fs']);

        $this->app->instance(RouteLoader::class, $loader);
    }



    /**
     * @return mixed
    */
    private function getControllerNamespace()
    {
         return $this->app['config']['app.namespaces.controllers'];
    }




    /**
     * @return mixed
    */
    private function getWebRoutePath()
    {
        return $this->app['config']['app.paths.routes.web'];
    }




    /**
     * @return mixed
    */
    private function getApiRoutePath()
    {
        return $this->app['config']['app.paths.routes.api'];
    }



    /**
     * @return mixed
    */
    private function getControllerPath()
    {
         return $this->app['config']['app.directories.controllers'];
    }




    /**
     * @return mixed
    */
    private function getRoutePaths()
    {
        $routes = $this->app['config']['app.paths.routes'];

        unset($routes['console']);

        return $routes;
    }
}