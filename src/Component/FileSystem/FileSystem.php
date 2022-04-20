<?php
namespace Laventure\Component\FileSystem;



use Laventure\Component\FileSystem\Collection\FileCollection;
use Laventure\Component\FileSystem\Locator\FileLocator;
use Laventure\Component\FileSystem\Stream\Stream;


/**
 * @FileSystem
*/
class FileSystem
{


      /**
       * @var
      */
      protected $locator;




      /**
       * FileSystem constructor.
       *
       * @param $root
      */
      public function __construct($root =  null)
      {
           $this->locator = new FileLocator($root);
      }






      /**
        * Set file system base path
        *
        * @param string $root
        * @return $this
      */
      public function basePath(string $root): self
      {
           $this->locator->basePath($root);

           return $this;
      }




      /**
       * @param string $filename
       * @return string
      */
      public function locate(string $filename): string
      {
           return $this->locator->locate($filename);
      }




      /**
       * @param string $pattern
       * @return array|false|mixed
      */
      public function resources(string $pattern)
      {
           return $this->locator->locateResources($pattern);
      }





      /**
       * @param string $pattern
       * @return FileCollection
      */
      public function collection(string $pattern): FileCollection
      {
            return new FileCollection($this->resources($pattern));
      }




      /**
        * @param string $path
        * @return File
      */
      public function file(string $path): File
      {
          return new File($this->locate($path));
      }




      /**
       * @param string $path
       * @return void
      */
      public function load(string $path)
      {
           $this->file($path)->load();
      }



      /**
       * @param array $files
       * @return void
      */
      public function loadFiles(array $files)
      {
           foreach ($files as $file) {
                $this->load($file);
           }
      }




      /**
       * @param string $filename
       * @param string $content
       * @param bool $append
       * @return bool
      */
      public function write(string $filename, string $content, bool $append = true): bool
      {
           return $this->file($filename)->write($content, $append);
      }



      /**
       * Make file
       *
       * @param string $filename
       * @return bool
      */
      public function make(string $filename): bool
      {
           return $this->file($filename)->make();
      }




      /**
       * Replace old content by new content
       *
       * @param string $filename
       * @param $newContent
       * @return bool
       */
       public function rewrite(string $filename, $newContent): bool
       {
            return $this->file($filename)->rewrite($newContent);
       }





       /**
         * read file content
         *
         * @param $filename
         * @return string
        */
       public function read($filename): string
       {
            return $this->file($filename)->read();
       }





      /**
       * @param string $filename
       * @return array|string|string[]
      */
      public function info(string $filename)
      {
          return $this->file($filename)->getInfo();
      }




      /**
       * upload file
       *
       * @param $target
       * @param $filename
       * @return bool
      */
      public function move($target, $filename): bool
      {
           return $this->file($filename)->move($this->locate($target));
      }




      /**
       * @param string $filename
       * @param string $base64
      * @return bool|false
      */
      public function dumpFile(string $filename, string $base64): bool
      {
          return $this->file($filename)->dump($base64);
      }




     /**
      * @param string $filename
      * @param array $replacements
      * @return string|string[]
     */
     public function replace(string $filename, array $replacements)
     {
          return $this->file($filename)->replace($replacements);
     }




     /**
      * copy file to other destination
      *
      * @param string $from
      * @param string $destination
      * @return bool
     */
     public function copy(string $from, string $destination): bool
     {
          return $this->file($from)->copy($this->locate($destination));
     }



     /**
      * @param string $path
      * @return Stream
     */
     public function stream(string $path): Stream
     {
          return $this->file($path)->stream();
     }




      /**
       * @param string $filename
       * @return bool
      */
      public function remove(string $filename): bool
      {
           return $this->file($filename)->remove();
      }





      /**
       * @param string $path
       * @return bool
      */
      public function exists(string $path): bool
      {
          return $this->file($path)->exists();
      }





      /**
       * @param string $path
       * @return bool
      */
      public function has(string $path): bool
      {
           return $this->file($path)->is();
      }




      /**
       * @param string $path
       * @return array|false
      */
      public function asArray(string $path)
      {
          return $this->file($path)->toArray();
      }




      /**
       * @param string $path
       * @return false|string
      */
      public function asJson(string $path)
      {
          return $this->file($path)->toJson();
      }

}