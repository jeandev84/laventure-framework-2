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
    public function write($filename, $content, bool $append = true): bool
    {
         if ($append) {
             return file_put_contents($filename, $content.PHP_EOL, FILE_APPEND | LOCK_EX);
         }

         return file_put_contents($filename, $content);
    }
}