<?php
namespace Laventure\Component\FileSystem\Reader;


/**
 * @FileReaderInterface
*/
interface FileReaderInterface
{

    /**
     * Read file
     *
     * @param string $filename
     * @return mixed
    */
    public function read(string $filename);
}