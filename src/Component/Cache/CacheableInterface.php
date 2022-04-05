<?php
namespace Laventure\Component\Cache;


/**
 * @CacheableInterface
*/
interface CacheableInterface
{


    /**
     * Set cache
     *
     * @param string $key
     * @param $data
     * @return mixed
    */
    public function set(string $key, $data);




    /**
     * Get data from the cache by given key
     *
     * @param string $key
     * @return mixed
    */
    public function get(string $key);




    /**
     * Delete the specified data from the cache by given key
     *
     * @param string $key
     * @return mixed
    */
    public function delete(string $key);




    /**
     * Check if the specific cache key exist
     *
     * @param string $key
     * @return mixed
    */
    public function exists(string $key);
}