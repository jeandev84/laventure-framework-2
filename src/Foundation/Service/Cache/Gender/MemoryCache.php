<?php
namespace Laventure\Foundation\Service\Cache\Gender;


use Laventure\Foundation\Service\Cache\CacheInterface;


/**
 * @MemoryCache
*/
class MemoryCache implements CacheInterface
{

    /**
     * @inheritDoc
     */
    public function set(string $key, $data)
    {
        // TODO: Implement set() method.
    }

    /**
     * @inheritDoc
     */
    public function get(string $key)
    {
        // TODO: Implement get() method.
    }

    /**
     * @inheritDoc
     */
    public function delete(string $key)
    {
        // TODO: Implement delete() method.
    }

    /**
     * @inheritDoc
    */
    public function exists(string $key)
    {
        // TODO: Implement exists() method.
    }
}