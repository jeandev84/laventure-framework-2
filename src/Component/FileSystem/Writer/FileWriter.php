<?php
namespace Laventure\Component\FileSystem\Writer;



/**
 * @FileWriter
*/
class FileWriter implements FileWriterInterface
{
    /**
     * @inheritDoc
     * @return bool
    */
    public function write($filename, $content): bool
    {
         return file_put_contents($filename, $content.PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}