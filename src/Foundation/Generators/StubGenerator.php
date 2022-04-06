<?php
namespace Laventure\Foundation\Generators;


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
       protected function getStubPath(): string
       {
            return __DIR__.'/stubs';
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

          $this->fileSystem->root($this->getStubPath());

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
     * @return bool
    */
    public function writeTo(string $targetPath, string $stub): bool
    {
        $this->fileSystem->root($this->getProjectDir());

        if ($this->fileSystem->exists($targetPath)) {
             trigger_error("File {$targetPath} already exist.");
             return false;
        }

        if($this->fileSystem->write($targetPath, $stub)) {
            $this->generatedPaths[] = $targetPath;
            return true;
        }

        return false;
    }




    /**
     * @param string $targetPath
     * @param string $stub
     * @return bool
    */
    public function append(string $targetPath, string $stub): bool
    {
         $this->fileSystem->root($this->getProjectDir());
         return $this->fileSystem->write($targetPath, $stub);
    }



    /**
     * @param string $fileName
     * @return bool
    */
    public function generateFile(string $fileName): bool
    {
        $this->fileSystem->root($this->getProjectDir());

        if ($this->fileSystem->exists($fileName)) {
            trigger_error("File {$fileName} already exist.");
            return false;
        }

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