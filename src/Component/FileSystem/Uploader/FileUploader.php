<?php
namespace Laventure\Component\FileSystem\Uploader;




/**
 * @FileUploader
*/
class FileUploader implements FileUploaderInterface
{

    /**
     * @param $target
     * @param $filename
     * @return bool
    */
    public function upload($target, $filename): bool
    {
         return move_uploaded_file($target, $filename);
    }


    /**
     * @param $from
     * @param $destination
     * @param null $context
     * @return bool
     */
    public function copy($from, $destination, $context = null): bool
    {
         return copy($from, $destination, $context);
    }
}