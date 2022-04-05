<?php
namespace Laventure\Component\Container\ServiceProvider;


use Laventure\Component\Container\ServiceProvider\Contract\ServiceProviderInterface;


/**
 * Class ServiceProvider
 *
 * @package Laventure\Component\Container\ServiceProvider\Contract
*/
abstract class ServiceProvider implements ServiceProviderInterface
{

    use ServiceProviderTrait;



    /**
     * register provider
     *
     * @return mixed
    */
    abstract public function register();
}