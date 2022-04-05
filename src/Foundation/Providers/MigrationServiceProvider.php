<?php
namespace Laventure\Foundation\Providers;

use Laventure\Component\Container\ServiceProvider\ServiceProvider;
use Laventure\Component\Database\Migration\Contract\MigratorInterface;
use Laventure\Component\Database\Migration\Migrator;
use Laventure\Foundation\Loaders\MigrationLoader;


/**
 * @MigrationServiceProvider
*/
class MigrationServiceProvider extends ServiceProvider
{


    /**
     * @var \string[][]
    */
    protected $provides = [
        Migrator::class  => ['migrator', MigratorInterface::class]
    ];



    /**
     * @inheritDoc
    */
    public function register()
    {
        $this->app->singleton(Migrator::class, function () {
            return new Migrator($this->app['db.connection']);
        });

    }


    public function terminate()
    {
        $loader = new MigrationLoader($this->app, $this->app[Migrator::class]);
        $loader->setResourcePattern('app/Migration/*.php')
               ->setNamespace('App\\Migration')
               ->setLocatePath('app/Migration');

        $loader->loadMigrations($this->app['@fs']);
        $this->app->instance(MigrationLoader::class, $loader);
    }

}