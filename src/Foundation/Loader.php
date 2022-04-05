<?php
namespace Laventure\Foundation;


use Laventure\Component\Container\Container;
use Laventure\Component\FileSystem\FileSystem;


/**
 * @Loader
*/
abstract class Loader
{

      /**
       * @var Container
      */
      protected $app;



      /**
       * @var string
      */
      protected $path;




      /**
       * @var array
      */
      protected $paths;




      /**
       * @var string
      */
      protected $resourcePattern;




      /**
       * @var string
      */
      protected $namespace;




      /**
        * @param Container $app
      */
      public function __construct(Container $app)
      {
            $this->app = $app;
      }



      /**
       * @param string $path
       * @return $this
      */
      public function setLocatePath(string $path): self
      {
           $this->path = $path;

           return $this;
      }




      /**
       * @param string $namespace
       * @return $this
      */
      public function setNamespace(string $namespace): self
      {
           $this->namespace = $namespace;

           return $this;
      }




      /**
       * @param string $pattern
       * @return $this
      */
      public function setResourcePattern(string $pattern): self
      {
           $this->resourcePattern = $pattern;

           return $this;
      }




      /**
       * @param string $fileName
       * @return string
      */
      public function generateLocatePath(string $fileName): string
      {
          $path = $this->trimmedPath($this->getLocatePath());

          return $this->generatePath($path, $fileName);
      }




      /**
       * @param string $path
       * @param string $fileName
       * @return string
      */
      public function generatePath(string $path, string $fileName): string
      {
          return sprintf("%s/%s.php", $path, $fileName);
      }





      /**
       * @return mixed
      */
      public function getLocatePath()
      {
           return $this->path;
      }





      /**
       * @return mixed
      */
      public function getNamespace()
      {
           return $this->trimmedNamespace($this->namespace);
      }




      /**
       * @return string
      */
      public function getResourcePattern(): string
      {
           return $this->resourcePattern;
      }




      /**
       * @param string|null $className
       * @return mixed|string
      */
      public function loadNamespace(string $className = null)
      {
          $namespace = $this->getNamespace();

          if ($className) {
              return sprintf('%s\\%s', $namespace, $className);
          }

          return $namespace;
      }



      /**
       * @return mixed
      */
      protected function getFileNames(FileSystem $fileSystem): array
      {
          $pattern = $this->getResourcePattern();

          return $fileSystem->collection($pattern)->getFileNames();
      }



      /**
       * @param mixed $paths
       * @return $this
      */
      public function setLoadPaths($paths): self
      {
          $this->paths = (array) $paths;

          return $this;
      }




      /**
       * @return array
      */
      public function getLoadPaths(): array
      {
         return $this->paths;
      }




      /**
       * @param FileSystem $fileSystem
       * @return void
      */
      public function loadPaths(FileSystem $fileSystem)
      {
          $fileSystem->loadFiles($this->getLoadPaths());
      }




      /**
       * @param $path
       * @return string
      */
      protected function trimmedPath($path): string
      {
          return trim($path, '\\/');
      }




      /**
       * @param string $namespace
       * @return string
      */
      protected function trimmedNamespace(string $namespace): string
      {
          return trim($namespace, '\\');
      }

}