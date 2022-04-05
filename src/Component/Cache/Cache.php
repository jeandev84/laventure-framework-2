<?php
namespace Laventure\Component\Cache;


/**
 * @Cache
*/
class Cache
{
    /**
     * @var CacheableInterface
    */
    protected $cacheable;



    /**
     * Cache constructor.
     * @param CacheableInterface $cacheable
     */
    public function __construct(CacheableInterface $cacheable)
    {
        $this->cacheable = $cacheable;
    }



    /**
     * @param $key
     * @param $data
     * @return $this
    */
    public function set($key, $data): Cache
    {
        $this->cacheable->set($key, $data);

        return $this;
    }



    /**
     * @param $key
     * @return mixed
    */
    public function get($key)
    {
        return $this->cacheable->get($key);
    }




    /**
     * @param $key
    */
    public function delete($key)
    {
        $this->cacheable->delete($key);
    }





    /**
     * @param $key
     * @return mixed
    */
    public function exists($key)
    {
        return $this->cacheable->exists($key);
    }
}