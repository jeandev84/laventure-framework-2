<?php
namespace Laventure\Component\Container\ServiceProvider;


use Laventure\Component\Container\Container;
use Laventure\Component\Container\Contract\ContainerInterface;


/**
 * Class ServiceProviderTrait
 * @package Laventure\Component\Container\Common
 */
trait ServiceProviderTrait
{

    /**
     * @var Container
    */
    public $app;




    /**
     * @var array
    */
    protected $provides = [];




    /**
     * @param Container $app
    */
    public function setContainer(Container $app)
    {
          $this->app = $app;
    }



    /**
     * @return ContainerInterface
    */
    public function getContainer(): ContainerInterface
    {
       return $this->app;
    }



    /**
     * @return array
    */
    public function getProvides(): array
    {
        return $this->provides;
    }



    /**
     * @return void
    */
    public function terminate() {}
}