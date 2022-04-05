<?php
namespace Laventure\Component\FileSystem\Collection;

use Laventure\Component\FileSystem\File;


/**
 * @FileCollection
*/
class FileCollection implements FileCollectionInterface
{



      /**
       * @var File[]
      */
      protected $files = [];




      /**
       * @var array
      */
      protected $paths = [];




      /**
       * @var array
      */
      protected $data = [];





      /**
       * @param array $files
      */
      public function __construct(array $files = [])
      {
            if ($files) {
                $this->addFiles($files);
            }
      }




      /**
       * Add file object
       *
       * @param File $file
       * @return $this
      */
      public function add(File $file): self
      {
           $name = $file->getFilename();
           $this->paths[$name] = $file->getPath();
           $this->data[$name]  = $file->getData();
           $this->files[$name] = $file;

           return $this;
      }




      /**
       * Add file object
       *
       * @param array $files
       * @return $this
      */
      public function addFiles(array $files): self
      {
           foreach ($files as $path) {
                $this->add(new File($path));
           }

           return $this;
      }




      /**
       * Remove file
       *
       * @param File $file
       * @return bool
      */
      public function removeFile(File $file): bool
      {
           return $file->remove();
      }




      /**
       * @param string $name
       * @return File|null
      */
      public function get(string $name): ?File
      {
          return $this->files[$name] ?? null;
      }





      /**
       * @param string $name
       * @return void
      */
      public function remove(string $name)
      {
           if ($file = $this->get($name)) {
               $this->removeFile($file);
           }
      }




      /**
       * @param string $name
       * @return bool
      */
      public function has(string $name): bool
      {
          return isset($this->files[$name]);
      }




      /**
       * File collection
       *
       * @return void
      */
      public function removeFiles()
      {
           foreach ($this->files as $file) {

               $this->removeFile($file);
               $this->removeCollections($file);
           }
      }



      /**
       * @param File $file
       * @return void
      */
      public function removeCollections(File $file)
      {
           $name = $file->getFilename();

           unset($this->files[$name], $this->paths[$name], $this->data[$name]);
      }




      /**
       * Get all files
       *
       * @return File[]
      */
      public function getFiles(): array
      {
           return $this->files;
      }



      /**
       * Get file paths
       *
       * @return string[]
      */
      public function getPaths(): array
      {
           return $this->paths;
      }



      /**
       * @return array
      */
      public function getData(): array
      {
           return $this->data;
      }




      /**
       * @return int[]|string[]
      */
      public function getFileNames(): array
      {
          return array_keys($this->files);
      }
}