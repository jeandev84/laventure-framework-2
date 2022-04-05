<?php
namespace Laventure\Component\Container\ServiceProvider\Contract;


/**
 * @ServiceProviderInterface
*/
interface ServiceProviderInterface
{

    /**
     * Register service in to container
     *
     * @return mixed
    */
    public function register();
}