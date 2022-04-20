<?php
namespace Laventure\Component\Container\Contract;


use Laventure\Component\Container\Container;

/**
 * @ContainerAwareInterface
*/
interface ContainerAwareInterface
{

    /**
     * @param Container $container
     * @return mixed
    */
    public function setContainer(Container $container);



    /**
     * @return Container
    */
    public function getContainer(): Container;
}