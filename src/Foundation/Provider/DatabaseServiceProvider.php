<?php
namespace Laventure\Foundation\Provider;


use Laventure\Component\Container\ServiceProvider\ServiceProvider;
use Laventure\Component\Database\Connection\Contract\ConnectionInterface;
use Laventure\Component\Database\Manager;
use Laventure\Component\Database\ORM\Manager\Contract\EntityManagerInterface;
use Laventure\Component\Database\ORM\Manager\Contract\EntityManagerServiceInterface;
use Laventure\Component\Database\ORM\Manager\Contract\ObjectManager;
use Laventure\Component\Database\ORM\Manager\EntityManager;
use Laventure\Component\Database\ORM\Manager\Fixtures\FixtureManager;
use Laventure\Component\Database\Schema\Schema;
use Laventure\Foundation\Loader\EntityLoader;
use Laventure\Foundation\Loader\FixtureLoader;
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
         if (! $this->getConnectionType()) {
              exit("Unable connection type inside .env (DB_TYPE)\n");
         }

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
         $this->registerFixtureLoader();
         $this->registerEntityLoader();
    }



    /**
     * @return void
    */
    private function registerFixtureLoader()
    {
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
}