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
      protected $namespace = "";




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
      public function loadLocatePath(string $fileName): string
      {
          $path = $this->trimmedPath($this->getLocatePath());

          return $this->makePath($path, $fileName);
      }




      /**
       * @param string $path
       * @param string $fileName
       * @return string
      */
      protected function makePath(string $path, string $fileName): string
      {
           return sprintf("%s/%s.php", $path, $fileName);
      }





      /**
       * @return string
       */
      public function getLocatePath(): string
      {
           return $this->path;
      }




      /**
       * @param string $namespace
       * @param string|null $suffix
       * @return string
      */
      protected function makeNamespace(string $namespace, string $suffix = null): string
      {
           if ($suffix) {
               $suffix = "\\". trim($suffix, "\\");
           }

           return sprintf('%s%s', $namespace, $suffix);
      }



      /**
       * @param string|null $module
       * @return string
      */
      public function getNamespace(string $module = null): string
      {
           $namespace = $this->trimmedNamespace($this->namespace);

           return $this->makeNamespace($namespace, $module);
      }




      /**
       * @return string
      */
      public function getResourcePattern(): string
      {
           return $this->resourcePattern;
      }




      /**
       * @param string|null $class
       * @return string
      */
      public function loadNamespace(string $class = null): string
      {
          $namespace = $this->getNamespace();

          if ($class) {
              return sprintf('%s\\%s', $namespace, $class);
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
       * @param string $path
       * @return string
      */
      protected function trimmedPath(string $path): string
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