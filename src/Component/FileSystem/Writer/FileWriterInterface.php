<?php
namespace Laventure\Component\FileSystem\Writer;


/**
 * @FileWriterInterface
*/
interface FileWriterInterface
{

    /**
     * Write content into the file
     *
     * @param $filename
     * @param $content
     * @return mixed
    */
    public function write($filename, $content);
}