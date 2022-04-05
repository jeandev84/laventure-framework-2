<?php
namespace Laventure\Foundation\Providers;


use Laventure\Component\Container\Container;
use Laventure\Component\Container\ServiceProvider\Contract\BootableServiceProvider;
use Laventure\Component\Container\ServiceProvider\ServiceProvider;
use Laventure\Component\Dotenv\Dotenv;
use Laventure\Foundation\Application;
use Laventure\Foundation\Facade\Console\Schedule;
use Laventure\Foundation\Facade\Database\DB;
use Laventure\Foundation\Facade\Database\Schema;
use Laventure\Foundation\Facade\Routing\Route;


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
         $this->loadWhoops();
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



    public function loadWhoops()
    {

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
        foreach ($this->getClassAliases() as $alias => $className) {
            \class_alias($className, $alias);
        }
    }




    /**
     * Load facades
     *
     * @return void
    */
    protected function loadFacades()
    {
        $this->app->addFacades($this->getFacadeStack());
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
    protected function getFacadeStack(): array
    {
        return [
            Route::class,
            DB::class,
            Schema::class,
            Schedule::class
        ];
    }
}