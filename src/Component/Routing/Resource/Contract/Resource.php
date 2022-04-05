<?php
namespace Laventure\Component\Routing\Resource\Contract;


use Laventure\Component\Routing\Router;


/**
 * Resource interface
 *
 * @Resource
*/
interface Resource
{


    /**
     * Get resource name
     *
     * @return string
    */
    public function getName(): string;





    /**
     * Get resource controller
     *
     * @return string
    */
    public function getController(): string;




    /**
     * Configure routes
     *
     * @param Router $router
     * @return mixed
    */
    public function mapRoutes(Router $router);





    /**
     * Get resource routes
     *
     * @return mixed
    */
    public function getRoutes();

}