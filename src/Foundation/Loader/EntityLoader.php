<?php
namespace Laventure\Foundation\Loader;


use Laventure\Component\Container\Container;
use Laventure\Foundation\Loader\Common\Loader;


/**
 * @EntityLoader
*/
class EntityLoader extends Loader
{

      /**
       * @var string
      */
      protected $repositoryNamespace;




      /**
       * @var string
      */
      protected $repositoryPath;






      /**
        * @param Container $app
      */
      public function __construct(Container $app)
      {
          parent::__construct($app);
      }




      /**
       * @param string $namespace
       * @return Loader|EntityLoader
      */
      public function setEntityNamespace(string $namespace)
      {
            return $this->setNamespace($namespace);
      }




      /**
       * Set entity locate path
       *
       * @param string $path
       * @return Loader|EntityLoader
      */
      public function setEntityLocatePath(string $path)
      {
            return $this->setLocatePath($path);
      }





      /**
       * Get entity namespace
       *
       * @param string|null $module
       * @return string
      */
      public function loadEntityNamespace(string $module = null): string
      {
             return $this->getNamespace($module);
      }




      /**
       * @param string $path
       * @return string
      */
      public function loadEntityPath(string $path): string
      {
           return $this->loadLocatePath($path);
      }





      /**
       * Set repository namespace
       *
       * @param string $namespace
       * @return $this
      */
      public function setRepositoryNamespace(string $namespace): self
      {
            $this->repositoryNamespace = $namespace;

            return $this;
      }





      /**
       * @param string|null $module
       * @return string
      */
      public function loadRepositoryNamespace(string $module = null): string
      {
            return $this->makeNamespace($this->repositoryNamespace, $module);
      }




      /**
       * @param string $path
       * @return $this
      */
      public function setRepositoryPath(string $path): self
      {
          $this->repositoryPath = $path;

          return $this;
      }





      /**
       * @param string $path
       * @return string
      */
      public function loadRepositoryPath(string $path): string
      {
            return $this->makePath($this->repositoryPath, sprintf('%sRepository', $path));
      }




      /**
        * @param string $entityClass
        * @param string|null $module
        * @return string
      */
      public function getFullNamespaceEntityClass(string $entityClass, string $module = null): string
      {
           $module = trim($module, '\\');

           return sprintf('%s\\%s', $this->getNamespace($module), $entityClass);
      }
}