<?php
namespace Laventure\Foundation\Providers;


use Laventure\Component\Container\ServiceProvider\ServiceProvider;
use Laventure\Component\Database\Connection\Contract\ConnectionInterface;
use Laventure\Component\Database\Manager;
use Laventure\Component\Database\ORM\Manager\Contract\EntityManagerInterface;
use Laventure\Component\Database\ORM\Manager\Contract\EntityManagerServiceInterface;
use Laventure\Component\Database\ORM\Manager\Contract\ObjectManager;
use Laventure\Component\Database\ORM\Manager\EntityManager;
use Laventure\Component\Database\ORM\Manager\Fixtures\FixtureManager;
use Laventure\Component\Database\Schema\Schema;
use Laventure\Foundation\Loaders\FixtureLoader;
use Laventure\Foundation\Service\ORM\EntityManagerService;


/**
 * @DatabaseServiceProvider
*/
class DatabaseServiceProvider extends ServiceProvider
{

    /**
     * @var array
    */
    protected $provides = [
        Manager::class             => ['db.laventure'],
        ConnectionInterface::class => ['db.connection'],
        EntityManager::class       => [EntityManagerInterface::class, ObjectManager::class],
        Schema::class              => ['db.schema']
    ];




    /**
     * @inheritDoc
    */
    public function register()
    {
         $manager = new Manager();
         $manager->addConnection($this->app['config']['database']);
         $manager->bootEntityManager($service = new EntityManagerService($this->app));

         $this->app->instance(Manager::class, $manager);
         $this->app->instance(ConnectionInterface::class, $manager->getConnection());
         $this->app->instance(EntityManagerServiceInterface::class, $service);
         $this->app->instance(EntityManager::class, $manager->getEntityManager());
         $this->app->instance(Schema::class, $manager->schema());
    }




    /**
     * @inheritDoc
    */
    public function terminate()
    {
         $fixtureManager = new FixtureManager($this->em());
         $loader = new FixtureLoader($this->app, $fixtureManager);

         $loader->setResourcePattern($this->app['config']['app.resources.fixtures'])
                ->setLocatePath($this->app['config']['app.directories.fixtures'])
                ->setNamespace($this->app['config']['app.namespaces.fixtures']);

         $loader->addFixtures($this->app['@fs']);

         $this->app->instance(FixtureLoader::class, $loader);
    }


    /**
     * @return false|mixed|object|string|null
    */
    private function em()
    {
        return $this->app[EntityManager::class];
    }
}