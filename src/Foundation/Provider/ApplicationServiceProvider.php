<?php
namespace Laventure\Foundation\Provider;


use Laventure\Component\Container\Container;
use Laventure\Component\Container\ServiceProvider\Contract\BootableServiceProvider;
use Laventure\Component\Container\ServiceProvider\ServiceProvider;
use Laventure\Component\Dotenv\Dotenv;
use Laventure\Foundation\Application;
use Laventure\Foundation\Facade\Console\Schedule;
use Laventure\Foundation\Facade\Database\DB;
use Laventure\Foundation\Facade\Database\Schema;
use Laventure\Foundation\Facade\Routing\Route;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;


/**
 * @ApplicationServiceProvider
*/
class ApplicationServiceProvider extends ServiceProvider implements BootableServiceProvider
{

    /**
     * @inheritDoc
    */
    public function boot()
    {
         $this->loadEnvironments();
         $this->loadHelpers();
         $this->loadClassAliases();
         $this->loadFacades();
    }



    /**
     * @inheritDoc
    */
    public function register()
    {
         $this->app->singleton(Application::class, function () {
             return Container::getInstance();
         });
    }



    /**
     * Load environments
     *
     * @return void
    */
    protected function loadEnvironments()
    {
        Dotenv::create($this->app['path'])->load();

        $this->app->binds([
            'app.env'   => getenv('APP_ENV'),
            'app.debug' => getenv('APP_DEBUG')
        ]);

        $debug = getenv('APP_DEBUG');
        $mode  = getenv('APP_ENV');

        if ($debug && $mode === 'dev') {
             $this->loadWhoops();
        }
    }




    /**
     * @return void
    */
    protected function loadWhoops()
    {
         $whoops = new Run();
         $whoops->pushHandler(new PrettyPageHandler());
         $whoops->register();
    }





    /**
     * Load helpers
     *
     * @return void
    */
    protected function loadHelpers()
    {
        require __DIR__.'/../helpers.php';
    }



    /**
     * Load class alias
     *
     * @return void
    */
    protected function loadClassAliases()
    {
        foreach ($this->getClassAliases() as $alias => $class) {
            \class_alias($class, $alias);
        }
    }




    /**
     * Load facades
     *
     * @return void
    */
    protected function loadFacades()
    {
        $this->app->addFacades($this->getFacades());
    }






    /**
     * @return string[]
    */
    protected function getClassAliases(): array
    {
        return [
            "Route"    => Route::class,
            "Schedule" => Schedule::class,
            "DB"       => DB::class,
            "Schema"   => Schema::class
        ];
    }



    /**
     * @return string[]
    */
    protected function getFacades(): array
    {
        return [
            Route::class,
            DB::class,
            Schema::class,
            Schedule::class
        ];
    }
}