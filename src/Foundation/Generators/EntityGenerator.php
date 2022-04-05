<?php
namespace Laventure\Foundation\Generators;



use Laventure\Component\FileSystem\FileSystem;
use Laventure\Foundation\Application;
use Laventure\Foundation\Loaders\EntityLoader;


/**
 * @EntityGenerator
*/
class EntityGenerator extends StubGenerator
{


      /**
       * @var EntityLoader
      */
      protected $loader;




      /**
       * EntityGenerator constructor.
       *
       * @param Application $app
       * @param FileSystem $fileSystem
       * @param EntityLoader $loader
      */
      public function __construct(
          Application $app,
          FileSystem $fileSystem,
          EntityLoader $loader
      )
      {
            parent::__construct($app, $fileSystem);
            $this->loader = $loader;
      }




      /**
        * Generate entity.
        *
        * @param string $entityPoint
        * @return bool
      */
      public function generate(string $entityPoint): bool
      {
            if ($this->generateEntity($entityPoint) && $this->generateRepository($entityPoint)) {
                return true;
            }

            return false;
      }




      /**
       * @param string $entryPoint
       * @return bool
      */
      public function generateEntity(string $entryPoint): bool
      {
           $parts       = $this->generateClassAndModule($entryPoint);
           $entityClass = $parts['className'];
           $module      = $parts['moduleName'];

           $entityStub = $this->generateStub('orm/entity/entity', [
              "EntityNamespace" => $this->loader->loadEntityNamespace($module),
              "EntityClass"     => $entityClass
           ]);


           return $this->writeTo($this->loader->loadEntityPath($entryPoint), $entityStub);
      }




      /**
       * Generate only repository
       *
       * @param string $entryPoint
       * @return bool
      */
      public function generateRepository(string $entryPoint): bool
      {
          $parts       = $this->generateClassAndModule($entryPoint);
          $entityClass = $parts['className'];
          $module      = $parts['moduleName'];

          $entityClassNamespace = $this->loader->getFullNamespaceEntityClass($entityClass, $module);

          $repositoryStub = $this->generateStub('orm/entity/repository', [
              "RepositoryNamespace"  => $this->loader->loadRepositoryNamespace($module),
              "RepositoryName"       => sprintf('%sRepository', $entityClass),
              "EntityClassNamespace" => $entityClassNamespace,
              "EntityClass"          => $entityClass
          ]);


          return $this->writeTo($this->loader->loadRepositoryPath($entryPoint), $repositoryStub);
      }


}