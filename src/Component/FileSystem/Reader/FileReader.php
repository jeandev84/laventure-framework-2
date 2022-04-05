<?php
namespace Laventure\Component\FileSystem\Reader;

/**
 * @FileReader
*/
class FileReader implements FileReaderInterface
{

    /**
     * @param string $filename
     * @return false|mixed|string
    */
    public function read(string $filename)
    {
        return file_get_contents($filename);
    }



    /**
     * @param string $filename
     * @return bool
    */
    public function readable(string $filename): bool
    {
        return is_readable($filename);
    }
}