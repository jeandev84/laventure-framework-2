<?php
namespace Laventure\Component\FileSystem\Scanner;


/**
 * @FileScanner
*/
class FileScanner implements FileScannerInterface
{

    /**
     * @inheritDoc
    */
    public function scan(string $pattern)
    {
         return scandir($pattern);
    }
}