<?php
namespace Laventure\Foundation\Service\Cache\Gender;


use Laventure\Component\FileSystem\FileSystem;
use Laventure\Foundation\Service\Cache\CacheInterface;


/**
 * @FileCache
 */
class FileCache implements CacheInterface
{

    /**
     * @var string
     */
    protected $cacheDir = '';




    /**
     * @var string
     */
    protected $cacheExtension;




    /**
     * @var FileSystem
     */
    protected $fileSystem;




    /**
     * @param string $cacheDir
     * @param string $cacheExtension
     */
    public function __construct(string $cacheDir, string $cacheExtension = 'txt')
    {
        $this->cacheDir       = $cacheDir;
        $this->cacheExtension = $cacheExtension;
        $this->fileSystem     = new FileSystem($cacheDir);
    }




    /**
     * @param string $cacheExtension
     * @return $this
     */
    public function withCacheExtension(string $cacheExtension): self
    {
        $this->cacheExtension = $cacheExtension;

        return $this;
    }





    /**
     * @param $key
     * @return string
     */
    public function cachePath($key): string
    {
        $cacheFilename = $this->cacheFilename($key);

        return $this->fileSystem->locate($cacheFilename);
    }



    /**
     * @param $key
     * @return string
    */
    public function cacheFilename($key): string
    {
        return sprintf('%s%s.%s',
    DIRECTORY_SEPARATOR,
            md5($key),
            $this->cacheExtension
        );
    }




    /**
     * @inheritDoc
    */
    public function set(string $key, $data, int $duration = 3600)
    {
        $content['data'] = $data;
        $content['end_time'] = time() + $duration;

        if ($this->fileSystem->write($this->cacheFilename($key), serialize($content))) {
            return true;
        }

        return false;
    }





    /**
     * @inheritDoc
     */
    public function exists(string $key): bool
    {
        return $this->fileSystem->exists($this->cacheFilename($key));
    }



    /**
     * @inheritDoc
     */
    public function get(string $key)
    {
        $cacheFile = $this->cacheFilename($key);

        if ($this->exists($key)) {

            $content = unserialize($this->fileSystem->read($cacheFile));

            if (time() <= $content['end_time']) {
                return $content['data'];
            }

            $this->fileSystem->remove($cacheFile);
        }

        return false;
    }



    /**
     * @inheritDoc
    */
    public function delete(string $key): bool
    {
        if ($this->exists($key)) {
            return $this->fileSystem->remove($this->cacheFilename($key));
        }

        return false;
    }
}