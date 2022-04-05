<?php
namespace Laventure\Component\FileSystem\Uploader;


/**
 * @FileUploaderInterface
*/
interface FileUploaderInterface
{
      /**
       * @param $target
       * @param $filename
       * @return mixed
      */
      public function upload($target, $filename);


      /**
        * @param $from
        * @param $destination
        * @param null $context
        * @return void
     */
      public function copy($from, $destination, $context = null);

}