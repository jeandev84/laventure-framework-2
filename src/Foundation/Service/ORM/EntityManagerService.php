<?php
namespace Laventure\Foundation\Service\ORM;


use Laventure\Component\Container\Container;
use Laventure\Component\Database\ORM\Manager\Contract\EntityManagerServiceInterface;
use Laventure\Component\Database\ORM\Repository\Contract\EntityRepositoryInterface;
use Laventure\Component\Database\ORM\Repository\EntityRepository;
use ReflectionClass;


/**
 * @EntityManagerService
*/
class EntityManagerService implements EntityManagerServiceInterface
{

       /**
        * @var Container
       */
       protected $app;




       /**
        * EntityManager constructor.
        *
        * @param Container $app
       */
       public function __construct(Container $app)
       {
             $this->app = $app;
       }






      /**
       * @param string $entityClass
       * @return string
      */
      public function createRepositoryName(string $entityClass): string
      {
           return (function () use ($entityClass) {

               $entityNamespace = (new ReflectionClass($entityClass))->getNamespaceName();

               $repositoryClassName = str_replace($entityNamespace, "App\\Repository", $entityClass);

               return sprintf('%sRepository', $repositoryClassName);

           })();
      }



      /**
       * @inheritDoc
      */
      public function createRepository(string $entityClass): EntityRepositoryInterface
      {
          $repositoryClass = $this->createRepositoryName($entityClass);

          $repository = $this->app->get($repositoryClass);

          if (! $repository instanceof EntityRepository) {
              trigger_error("Repository class $entityClass does not exist.");
          }

          return $repository;
      }




      /**
       * @inheritDoc
      */
      public function createTableName(string $entityClass): string
      {
          return (function ()  use ($entityClass) {

              $shortName = (new \ReflectionClass($entityClass))->getShortName();

              return mb_strtolower(trim($shortName, 's')) . 's';

          })();
      }




      /**
       * @inheritDoc
      */
      public function getEntityClassName($object): string
      {
           return (new \ReflectionObject($object))->getShortName();
      }
}