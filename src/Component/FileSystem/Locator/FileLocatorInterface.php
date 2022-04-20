<?php
namespace Laventure\Component\FileSystem\Locator;


/**
 * @FileLocatorInterface
*/
interface FileLocatorInterface
{
    /**
     * @param string $filename
     * @return mixed
    */
    public function locate(string $filename);



    /**
     * @param string $pattern
     * @param int $flags
     * @return mixed
    */
    public function locateResources(string $pattern, int $flags = 0);
}