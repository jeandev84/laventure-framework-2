<?php
namespace Laventure\Foundation\Provider;


use Laventure\Component\Container\ServiceProvider\ServiceProvider;
use Laventure\Component\Database\Connection\Contract\ConnectionInterface;
use Laventure\Component\Database\Manager;
use Laventure\Component\Database\Migration\Contract\MigratorInterface;
use Laventure\Component\Database\Migration\Migrator;
use Laventure\Component\Database\ORM\Manager\Contract\EntityManagerInterface;
use Laventure\Component\Database\ORM\Manager\Contract\EntityManagerServiceInterface;
use Laventure\Component\Database\ORM\Manager\Contract\ObjectManager;
use Laventure\Component\Database\ORM\Manager\EntityManager;
use Laventure\Component\Database\ORM\Manager\Fixtures\FixtureManager;
use Laventure\Component\Database\Schema\Schema;
use Laventure\Foundation\Loader\EntityLoader;
use Laventure\Foundation\Loader\FixtureLoader;
use Laventure\Foundation\Loader\MigrationLoader;
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
        Manager::class             => ['@database'],
        ConnectionInterface::class => ['@connection'],
        EntityManager::class       => [EntityManagerInterface::class, ObjectManager::class],
        Schema::class              => ['@schema'],
        Migrator::class            => ['@migrator', MigratorInterface::class],
    ];




    /**
     * @inheritDoc
    */
    public function register()
    {
         $manager = new Manager();
         $manager->addConnection($this->app['config']['database']);
         $this->app->instance(Manager::class, $manager);


         if ($manager->getConnection()) {

             $manager->bootEntityManager($service = new EntityManagerService($this->app));
             $this->app->instance(ConnectionInterface::class, $manager->getConnection());
             $this->app->instance(EntityManagerServiceInterface::class, $service);
             $this->app->instance(EntityManager::class, $manager->getEntityManager());
             $this->app->instance(Schema::class, $manager->schema());

             $this->app->singleton(Migrator::class, function () use ($manager){
                 $migrator = new Migrator($manager->getConnection());
                 $migrator->table($this->app['config']['database.migration_table']);
                 return $migrator;
             });
         }
    }




    /**
     * @inheritDoc
    */
    public function terminate()
    {
         if ($this->getConnection()) {
             $this->registerEntityLoader();
             $this->registerFixtureLoader();
             $this->registerMigrationLoader();
         }
    }



    /**
     * @return void
    */
    private function registerFixtureLoader()
    {
          // Fixture Loader
          $fixtureManager = new FixtureManager($this->em());
          $fixtureLoader  = new FixtureLoader($this->app, $fixtureManager);

          $fixtureLoader->setResourcePattern('app/Fixtures/*.php')
                        ->setLocatePath('app/Fixtures')
                        ->setNamespace('App\\Fixtures');

          $fixtureLoader->addFixtures($this->app['@fs']);

          $this->app->instance(FixtureLoader::class, $fixtureLoader);
    }




    /**
     * @return void
    */
    private function registerEntityLoader()
    {
         $entityLoader = new EntityLoader($this->app);

         $entityLoader->setEntityLocatePath("app/Entity")
                      ->setRepositoryPath("app/Repository")
                      ->setEntityNamespace("App\\Entity")
                      ->setRepositoryNamespace("App\\Repository");

         $this->app->instance(EntityLoader::class, $entityLoader);
    }




    /**
     * @return void
    */
    private function registerMigrationLoader()
    {
         // Migration loader
         $loader = new MigrationLoader($this->app, $this->app[Migrator::class]);
         $loader->setResourcePattern('app/Migration/*.php')
                ->setNamespace('App\\Migration')
                ->setLocatePath('app/Migration');

         $loader->loadMigrations($this->app['@fs']);
         $this->app->instance(MigrationLoader::class, $loader);
    }




    /**
     * @return false|mixed|object|string|null
    */
    private function em()
    {
         return $this->app[EntityManager::class];
    }




    /**
     * @return mixed
    */
    private function getConnectionType()
    {
        return $this->app['config']['database.connection'];
    }




    /**
     * @return mixed
    */
    private function getConnection()
    {
         return $this->app[Manager::class]->getConnection();
    }
}