<?php
namespace Laventure\Foundation\Generators;



use Laventure\Component\FileSystem\FileSystem;
use Laventure\Foundation\Application;

/**
 * @EntityGenerator
*/
class EntityGenerator extends StubGenerator
{

      /**
       * EntityGenerator constructor.
       *
       * @param Application $app
       * @param FileSystem $fileSystem
      */
      public function __construct(Application $app, FileSystem $fileSystem)
      {
            parent::__construct($app, $fileSystem);
      }




      /**
        * Generate entity.
        *
        * @param string $entityName
        * @return bool
      */
      public function generate(string $entityName): bool
      {
            $parts       = $this->generateClassAndModule($entityName);
            $entityClass = $parts['className'];
            $module      = $parts['moduleName'];

            if ($this->createdEntityAndRepository($entityClass, $module, $entityName)) {
                 return true;
            }

            return false;
      }




      /**
       * Created entity and repository.
       *
       * @param string $entityClass
       * @param string $path
       * @param string $module
       * @return bool
      */
      public function createdEntityAndRepository(string $entityClass, string $module, string $path): bool
      {
           return $this->generateEntity($entityClass, $module, $path)
                  && $this->generateRepository($entityClass, $module, $path);
      }





      /**
       * Generate only entity
       *
       * @param string $entityClass
       * @param string $module
       * @param string $path
       * @return bool
      */
      public function generateEntity(string $entityClass, string $module, string $path): bool
      {
            // make entity
            $entityStub = $this->generateStub('orm/entity/entity', [
               "EntityNamespace" => "App\\Entity{$module}",
               "EntityClass"     => $entityClass
            ]);


            return $this->writeTo("app/Entity/{$path}.php", $entityStub);
      }




     /**
      * Generate only repository
      *
      * @param string $entityClass
      * @param string $module
      * @param string $path
      * @return bool
     */
      public function generateRepository(string $entityClass, string $module, string $path): bool
      {
            // make repository
            $repositoryStub = $this->generateStub('orm/entity/repository', [
               "RepositoryNamespace"  => "App\\Repository{$module}",
               "RepositoryName"       => sprintf('%sRepository', $entityClass),
               "EntityClassNamespace" => "App\\Entity{$module}\\{$entityClass}",
               "EntityClass"          => $entityClass
            ]);

            return $this->writeTo("app/Repository/{$path}Repository.php", $repositoryStub);
      }

}