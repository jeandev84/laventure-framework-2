<?php
namespace Laventure\Component\FileSystem;



/**
 * @FileResolver
*/
trait FileResolver
{

    /**
     * @param string $path
     * @return string
    */
    public function resolvedPath(string $path): string
    {
        return str_replace(['\\', '/'], DIRECTORY_SEPARATOR, trim($path, '\\/'));
    }




    /**
     * @param string $path
     * @return false|string
    */
    public function realpath(string $path)
    {
         return realpath($path);
    }


    /**
     * @param string $root
     * @return string
    */
    protected function resolveRoot(string $root): string
    {
        return rtrim($this->realpath($root), '\\/');
    }

}