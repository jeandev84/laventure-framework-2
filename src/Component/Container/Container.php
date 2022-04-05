<?php
namespace Laventure\Component\Container;


use Exception;
use Laventure\Component\Container\Contract\AbstractContainer;
use Laventure\Component\Container\Contract\ContainerInterface;
use Laventure\Component\Container\Facade\Facade;
use Laventure\Component\Container\ServiceProvider\Contract\BootableServiceProvider;
use Laventure\Component\Container\ServiceProvider\ServiceProvider;
use ReflectionObject;


/**
 * @Container
 *
 * @package Laventure\Component\Container
*/
class Container extends AbstractContainer
{

    /**
     * @var Container
    */
    protected static $instance;




    /**
     * collection service providers
     *
     * @var array
    */
    protected $providers = [];





    /**
     * collection facades
     *
     * @var array
    */
    protected $facades = [];




    /**
     * @var array
    */
    protected $services = [];




    /**
     * Set container instance
     *
     * @param ContainerInterface|null $instance
    */
    public static function setInstance(ContainerInterface $instance = null): ?ContainerInterface
    {
          return static::$instance = $instance;
    }



    /**
     * @param string $abstract
     * @param $concrete
     * @return $this
    */
    public function singleton(string $abstract, $concrete): self
    {
        return $this->bind($abstract, $concrete, true);
    }



    /**
     * @param string $abstract
     * @return mixed
     * @throws Exception
    */
    public function factory(string $abstract)
    {
        return $this->make($abstract);
    }






    /**
     * Get container instance <Singleton>
     *
     * @return Container|static
    */
    public static function getInstance(): Container
    {
        if(is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }





    /**
     * @param array $services
     * @return void
    */
    public function addServices(array $services)
    {
        foreach ($services as $service) {
            $this->addService($service);
        }
    }




    /**
     * @param mixed $service
     * @return $this
    */
    public function addService($service): self
    {
        $service = $this->resolveGivenParameter($service);
        $serviceName = (new ReflectionObject($service))->getShortName();
        $this->services[$serviceName] = $service;
        $this->instance($serviceName, $service);

        return $this;
    }




    /**
     * @param string $name
     * @return void
    */
    public function removeService(string $name)
    {
        unset($this->services[$name]);
    }




    /**
     * Add service providers
     *
     * @param array $providers
     * @return $this
    */
    public function addProviders(array $providers): self
    {
        foreach ($providers as $provider) {
            $this->addProvider($provider);
        }

        return $this;
    }




    /**
     * Add service provider
     *
     * @param ServiceProvider|string $provider
     * @return $this
    */
    public function addProvider($provider): self
    {
        $provider = $this->resolveGivenParameter($provider);

        if ($provider instanceof ServiceProvider) {

            $provider->setContainer($this);

            if (! $this->hasProvider($provider)) {

                $this->bootProvider($provider);

                $provider->register();

                $this->registerProvides($provider);

                $provider->terminate();

                $this->providers[] = $provider;
            }
        }


        return $this;
    }




    /**
     * @param ServiceProvider $provider
     * @return void
    */
    protected function registerProvides(ServiceProvider $provider)
    {
        foreach ($provider->getProvides() as $abstract => $provides) {
             $provides = (array) $provides;
             foreach ($provides as $provide) {
                   $this->alias($provide, $abstract);
             }
        }
    }




    /**
     * @param ServiceProvider $provider
     * @return bool
    */
    public function hasProvider(ServiceProvider $provider): bool
    {
        return in_array($provider, $this->providers);
    }






    /**
     * Boot service provider
     *
     * @param ServiceProvider $provider
     * @return void
    */
    protected function bootProvider(ServiceProvider $provider)
    {
        if ($this->isBootable($provider)) {
            if (method_exists($provider, 'boot')) {
                $provider->boot();
            }
        }
    }





    /**
     * Get service providers
     *
     * @return array
    */
    public function getProviders(): array
    {
        return $this->providers;
    }




    /**
     * Determine if given provider is bootable
     *
     * @param ServiceProvider $provider
     * @return bool
    */
    protected function isBootable(ServiceProvider $provider): bool
    {
        $implements = class_implements($provider);

        return isset($implements[BootableServiceProvider::class]);
    }



    /**
     * Add facade
     *
     * @param Facade|string $facade
     * @return $this
     */
    public function addFacade($facade): Container
    {
        $facade = $this->resolveGivenParameter($facade);

        if ($facade instanceof Facade) {
            if (! \in_array($facade, $this->facades)) {
                $facade->setContainer($this);
                $this->facades[] = $facade;
            }
        }


        return $this;
    }




    /**
     * @param array $facades
     */
    public function addFacades(array $facades)
    {
        foreach ($facades as $facade) {
            $this->addFacade($this->get($facade));
        }
    }



    /**
     * @return array
    */
    public function getFacades(): array
    {
        return $this->facades;
    }

}