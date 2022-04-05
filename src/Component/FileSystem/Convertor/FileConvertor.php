<?php
namespace Laventure\Component\FileSystem\Convertor;


/**
 * @FileConvertor
*/
class FileConvertor implements FileConvertorInterface
{

    /**
     * @inheritDoc
    */
    public function toArray($filename)
    {
         return file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    }



    /**
     * @inheritDoc
    */
    public function toJson($filename)
    {
        return \json_encode($this->toArray($filename));
    }
}