<?php
namespace Laventure\Component\Container\ServiceProvider\Contract;


/**
 * Interface BootableServiceProvider
 *
 * @package Laventure\Component\Container\ServiceProvider\Contract
 */
interface BootableServiceProvider
{
    /**
     * Boot service provider
     *
     * @return mixed
    */
    public function boot();
}