<?php
namespace Laventure\Component\FileSystem\Locator;


use Laventure\Component\FileSystem\FileResolver;


/**
 * @FileLocator
*/
class FileLocator implements FileLocatorInterface
{


    use FileResolver;




    /**
     * @var string
    */
    protected $root;




    /**
     * @param mixed $root
    */
    public function __construct($root)
    {
          $this->root = $root;
    }




    /**
     * @param string $filename
     * @return string
    */
    public function locate(string $filename): string
    {
        if (! $this->root) {
            return $filename;
        }

        return $this->resolveRoot($this->root) . DIRECTORY_SEPARATOR . $this->resolvedPath($filename);
    }




    /**
     * @inheritDoc
    */
    public function resources(string $pattern, int $flags = 0)
    {
        return glob($this->locate($pattern), $flags);
    }



    /**
     * Scan directory
     *
     * @param string $pattern
     * @return array|false
    */
    public function scan(string $pattern)
    {
        return scandir($this->locate($pattern));
    }



    /**
     * @return mixed|string
    */
    public function getRoot()
    {
         return $this->root;
    }

}