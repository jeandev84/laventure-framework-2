<?php
namespace Laventure\Component\FileSystem;



use Laventure\Component\FileSystem\Convertor\FileConvertor;
use Laventure\Component\FileSystem\Reader\FileReader;
use Laventure\Component\FileSystem\Stream\Stream;
use Laventure\Component\FileSystem\Uploader\FileUploader;
use Laventure\Component\FileSystem\Writer\FileWriter;


/**
 * @File
*/
class File
{


     use FileResolver;



     /**
      * @var string
     */
     protected $path;




     /**
      * @var FileWriter
     */
     protected $writer;




     /**
      * @var FileReader
     */
     protected $reader;




     /**
      * @var FileUploader
     */
     protected $uploader;




     /**
      * @var Stream
     */
     protected $stream;



     /**
      * @var FileConvertor
     */
     protected $convertor;



     /**
      * File constructor
      *
      * @param string $path
     */
     public function __construct(string $path)
     {
          $this->path      = $path;
          $this->writer    = new FileWriter();
          $this->reader    = new FileReader();
          $this->uploader  = new FileUploader();
          $this->convertor = new FileConvertor();
          $this->stream    = new Stream($path);
     }



     /**
      * get file dirname
      *
      * @return string
     */
     public function getDirname(): string
     {
         return $this->getInfo( PATHINFO_DIRNAME);
     }




     /**
      * get base name
      *
      * @return string
     */
     public function getBasename(): string
     {
          return $this->getInfo(PATHINFO_BASENAME);
     }




     /**
      * get name of file
      *
      * @return string
     */
     public function getFilename(): string
     {
          return $this->getInfo( PATHINFO_FILENAME);
     }



     /**
      * get file extension
      *
      * @return string|null
     */
     public function getExtension(): ?string
     {
          return $this->getInfo(PATHINFO_EXTENSION);
     }




     /**
      * @param int|null $needle
      * @return array|string|string[]
     */
     public function getInfo(int $needle = null)
     {
          if (! $needle) {
              return pathinfo($this->path);
          }

          return pathinfo($this->path, $needle);
     }



     /**
      * @return false|int
     */
     public function getSize()
     {
         return filesize($this->path);
     }




     /**
      * @return string
     */
     public function getPath(): string
     {
         return $this->path;
     }



     /**
      * @return false|string
     */
     public function getRealPath()
     {
         return realpath($this->path);
     }




    /**
     * @return bool
    */
    public function exists(): bool
    {
         return file_exists($this->path);
    }




    /**
     * @return bool
    */
    public function is(): bool
    {
         return is_file($this->path);
    }




    /**
     * @return bool
    */
    public function executable(): bool
    {
         return is_executable($this->path);
    }




    /**
     * @return bool
    */
    public function make(): bool
    {
        $this->mkdir($this->getDirname());

        return touch($this->path);
    }




    /**
     * @return bool|string
    */
    public function mkdir($dir = null)
    {
         $dir = $dir ?? $this->path;

         if(! \is_dir($dir)) {
             return @mkdir($dir, 0777, true);
         }

         return $dir;
    }




    /**
     * upload file
     *
     * @param $target
     * @return bool
    */
    public function move($target): bool
    {
         $this->mkdir($target);

         return $this->uploader->upload($target, $this->path);
    }


    /**
     * @param string $destination
     * @param null $context
     * @return bool
    */
    public function copy(string $destination, $context = null): bool
    {
         return $this->uploader->copy($this->path, $destination, $context);
    }


    /**
     * @param $content
     * @param bool $append
     * @return bool|false
    */
    public function write($content, bool $append = true): bool
    {
         $this->make();

         return $this->writer->write($this->path, $content, $append);
    }


    /**
     * Rewrite new content to the file
     *
     * @param $newContent
     * @return bool
     */
    public function rewrite($newContent): bool
    {
         $this->remove();

         return $this->write($newContent);
    }





    /**
     * @param array $replacements
     * @return string|string[]
    */
    public function replace(array $replacements)
    {
        $keys   = array_keys($replacements);
        $values = array_values($replacements);

        return str_replace($keys, $values, $this->read());
    }


    /**
     * @return bool
     */
    public function read()
    {
        if (! $this->readable()) {
            return false;
        }

        return $this->reader->read($this->path);
    }




    /**
     * @return bool
    */
    public function readable(): bool
    {
         return $this->reader->readable($this->path);
    }




    /**
     * @param string $base64
     * @return bool|false
     */
    public function dump(string $base64): bool
    {
        $this->make();

        return $this->write(base64_decode($base64, true));
    }



    /**
     * Stream file
     *
     * @return Stream
    */
    public function stream(): Stream
    {
         return $this->stream;
    }




    /**
     * @return bool
    */
    public function remove(): bool
    {
         if (! $this->exists()) {
              return false;
         }

         return @unlink($this->path);
    }




    /**
     * Load file
     *
     * @return void
    */
    public function load()
    {
         if (! $this->exists()) {
              trigger_error("unable to load file path {$this->path} in method : ". __METHOD__);
         }

         require $this->path;
    }




    /**
     * Get array data
     *
     * @return mixed
    */
    public function getData()
    {
        if (! $this->exists()) {
            return [];
        }

        return require $this->path;
    }




    /**
     * Get file content as array
     *
     * @return array|false
    */
    public function toArray()
    {
        return $this->convertor->toArray($this->path);
    }




    /**
     * Get file content as json format
     *
     * @return false|string
    */
    public function toJson()
    {
        return $this->convertor->toJson($this->path);
    }




    /**
     * @return string
    */
    public function getResolvedPath(): string
    {
        return $this->resolvedPath($this->path);
    }

}