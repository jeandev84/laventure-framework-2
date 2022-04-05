<?php
namespace Laventure\Component\Config\Loaders;


use Exception;
use Laventure\Component\Config\Contract\Loader;



/**
 * @ArrayLoader
*/
class ArrayLoader implements Loader
{

    /**
     * @var array
     */
    protected $files;


    /**
     * ArrayLoader constructor.
     * @param array $files
     */
    public function __construct(array $files)
    {
        $this->files = $files;
    }


    /**
     * Parse method
     *
     * @return array
     * @throws Exception
    */
    public function parse(): array
    {
        $parsed = [];

        foreach ($this->files as $namespace => $path) {
            $parsed[$namespace] = @require $path;
        }

        return $parsed;
    }
}