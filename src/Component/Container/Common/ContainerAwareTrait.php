<?php
namespace Laventure\Component\Container\Common;


use Laventure\Component\Container\Contract\ContainerInterface;



/**
 * @see ContainerAwareTrait
 *
 * @package Laventure\Component\Container
*/
trait ContainerAwareTrait
{

    /**
     * @var ContainerInterface
    */
    protected $container;




    /**
     * @param ContainerInterface $container
     * @return mixed
    */
    public function setContainer(ContainerInterface $container)
    {
           $this->container = $container;
    }


    /**
     * @return ContainerInterface
    */
    public function getContainer(): ContainerInterface
    {
         return $this->container;
    }
}