<?php
namespace Laventure\Component\FileSystem\Convertor;


/**
 * @FileConvertorInterface
*/
interface FileConvertorInterface
{

     /**
      * @param $filename
      * @return mixed
     */
     public function toArray($filename);


     /**
      * @param $filename
      * @return mixed
     */
     public function toJson($filename);
}