<?php
namespace Laventure\Foundation\Generator;


use Laventure\Component\FileSystem\FileSystem;
use Laventure\Foundation\Application;


/**
 * @StubGenerator
*/
class StubGenerator
{

       /**
        * @var Application
       */
       protected $app;




       /**
        * @var FileSystem
       */
       protected $fileSystem;




       /**
       * @var string
       */
       protected $generatedPaths = [];




       /**
        * StubGenerator constructor.
       */
       public function __construct(Application $app, FileSystem $fileSystem)
       {
             $this->app        = $app;
             $this->fileSystem = $fileSystem;
       }




       /**
         * @return string
       */
       protected function getProjectDir(): string
       {
            return $this->app->getPath();
       }



       /**
        * @return string
       */
       protected function getRoot(): string
       {
            return __DIR__.'/stubs';
       }




       /**
        * @param string $path
        * @return string
       */
       protected function generateStubPath(string $path): string
       {
            return realpath($this->getRoot() . '/' . trim($path, '\\/'));
       }



      /**
       * @return string
      */
      protected function getStubExtension(): string
      {
            return 'stub';
      }




     /**
      * Generate a file  from stub
      *
      * @param $filename
      * @param $replacements
     * @return string|string[]
    */
    public function generateStub($filename, $replacements)
    {
          $replacements['GenerateTime']    =  date('d/m/Y H:i:s');
          $replacements['ApplicationName'] =  $this->app->getName();

          $this->fileSystem->basePath($this->getRoot());

          return $this->fileSystem->replace(
              sprintf('%s.%s', $filename, $this->getStubExtension()),
              $replacements
          );
    }


    /**
     * Write content stub to the target path
     *
     * @param string $targetPath
     * @param string $stub
     * @param bool $append
     * @return bool
    */
    public function writeTo(string $targetPath, string $stub, bool $append = true): bool
    {
        $this->fileSystem->basePath($this->getProjectDir());

        if ($this->fileSystem->exists($targetPath)) {
             trigger_error("File {$targetPath} already exist.");
             return false;
        }

        if($this->fileSystem->write($targetPath, $stub, $append)) {
            $this->generatedPaths[] = $targetPath;
            return true;
        }

        return false;
    }



    /**
     * @param string $targetPath
     * @param string $stub
     * @param bool $append
     * @return bool
    */
    public function append(string $targetPath, string $stub, bool $append = true): bool
    {
         $this->generateFile($targetPath);

         return $this->fileSystem->write($targetPath, $stub, $append);
    }



    /**
     * @param string $fileName
     * @return bool
    */
    public function generateFile(string $fileName): bool
    {
        $this->fileSystem->basePath($this->getProjectDir());

        if($this->fileSystem->make($fileName)) {
            $this->generatedPaths[] = $fileName;
            return true;
        }

        return false;
    }



    /**
     * @return string
    */
    public function getGeneratedPath(): string
    {
        return $this->generatedPaths[0];
    }




    /**
     * @return array
    */
    public function getGeneratedPaths(): array
    {
        return $this->generatedPaths;
    }



    /**
     * @param string $entryName
     * @return array
    */
    public function generateClassAndModule(string $entryName): array
    {
        $entryParts = explode('/', $entryName);
        $className  = ucfirst(end($entryParts));
        $module     = str_replace($className, '', implode('\\', $entryParts));
        $module     = $module ? '\\'. trim(ucfirst($module), '\\') : '';

        return ['className' => $className, 'moduleName' => ucfirst($module)];
    }

}