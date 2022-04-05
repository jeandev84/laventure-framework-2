<?php
namespace Laventure\Component\Http\Bag\Contract;


/**
 * @ParameterBagInterface
*/
interface ParameterBagInterface
{
    /**
     * Determine if given key param exist in bag
     *
     * @param $key
     * @return bool
    */
    public function has($key): bool;



    /**
     * Get value parameter from bag
     *
     * @param string $key
     * @param null $default
     * @return mixed|null
    */
    public function get(string $key, $default = null);
}