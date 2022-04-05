<?php
namespace Laventure\Component\FileSystem\Scanner;



/**
 * @FileScannerInterface
*/
interface FileScannerInterface
{

       /**
        * @param string $pattern
        * @return mixed
       */
       public function scan(string $pattern);
}