<?php
namespace Laventure\Component\Container\Contract;


use Laventure\Component\Container\Container;

/**
 * @ContainerAwareInterface
*/
interface ContainerAwareInterface
{

    /**
     * @param ContainerInterface $container
     * @return mixed
    */
    public function setContainer(ContainerInterface $container);



    /**
     * @return Container
    */
    public function getContainer(): ContainerInterface;
}