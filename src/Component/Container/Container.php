<?php
namespace Laventure\Component\Container;


use ArrayAccess;
use Closure;
use Exception;
use InvalidArgumentException;
use Laventure\Component\Container\Contract\ContainerAwareInterface;
use Laventure\Component\Container\Contract\ContainerInterface;
use Laventure\Component\Container\Exception\ContainerException;
use Laventure\Component\Container\Facade\Facade;
use Laventure\Component\Container\ServiceProvider\Contract\BootableServiceProvider;
use Laventure\Component\Container\ServiceProvider\ServiceProvider;
use ReflectionClass;
use ReflectionException;
use ReflectionObject;
use ReflectionParameter;


/**
 * @Container
 *
 * @package Laventure\Component\Container
*/
class Container implements ContainerInterface, ArrayAccess
{


    /**
     * @var Container
    */
    protected static $instance;



    /**
     * storage all bound params
     *
     * @var array
    */
    protected $bindings = [];



    /**
     * storage all instances
     *
     * @var array
    */
    protected $instances = [];




    /**
     * storage all resolved params
     *
     * @var array
    */
    protected $resolved  = [];



    /**
     * storage all aliases
     *
     * @var array
    */
    protected $aliases = [];



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



    /**
     * Get bindings params
     *
     * @return array
     */
    public function getBindings(): array
    {
        return $this->bindings;
    }




    /**
     * Get all instances
     *
     * @return array
     */
    public function getInstances(): array
    {
        return $this->instances;
    }




    /**
     * Get resolved params
     *
     * @return array
     */
    public function getResolved(): array
    {
        return $this->resolved;
    }



    /**
     * @return array
     */
    public function getAliases(): array
    {
        return $this->aliases;
    }




    /**
     * @param string $abstract
     * @return mixed
     */
    public function getConcreteContext(string $abstract)
    {
        if(! $this->bounded($abstract)) {
            return $abstract;
        }

        return $this->bindings[$abstract]['concrete'];
    }



    /**
     * @param string $abstract
     * @param null $concrete
     * @param bool $shared
     * @return $this
     */
    public function bind(string $abstract, $concrete = null, bool $shared = false): self
    {
        if(\is_null($concrete)) {
            $concrete = $abstract;
        }

        $this->bindings[$abstract] = compact('concrete', 'shared');

        return $this;
    }





    /**
     * Bind many params in the container
     *
     * @param array $bindings
     */
    public function binds(array $bindings)
    {
        foreach ($bindings as $abstract => $concrete) {
            $this->bind($abstract, $concrete);
        }
    }





    /**
     * Determine if the given param is bound
     *
     * @param $abstract
     * @return bool
     */
    public function bound($abstract): bool
    {
        return isset($this->bindings[$abstract]);
    }



    /**
     * @param $object
     * @return string
     */
    protected function getClassName($object): string
    {
        return (new ReflectionObject($object))->getShortName();
    }




    /**
     * @param $abstract
     * @return bool
     */
    public function shared($abstract): bool
    {
        return $this->hasInstance($abstract) || $this->isShared($abstract);
    }



    /**
     * Share a parameter
     *
     * @param $abstract
     * @param $concrete
     * @return mixed
     */
    public function makeSingleton($abstract, $concrete)
    {
        if(! $this->hasInstance($abstract)) {
            $this->instances[$abstract] = $concrete;
        }

        return $this->instances[$abstract];
    }




    /**
     * Set instance
     *
     * @param $abstract
     * @param mixed $concrete
     * @return self
     */
    public function instance($abstract, $concrete): self
    {
        $this->instances[$abstract] = $concrete;

        return $this;
    }




    /**
     * @param array $instances
     * @return $this
     */
    public function instances(array $instances): self
    {
        $this->instances = array_merge($this->instances, $instances);

        return $this;
    }




    /**
     * @param string $abstract
     * @param array $parameters
     * @return mixed
     */
    public function make(string $abstract, array $parameters = [])
    {
        return (function () use ($abstract, $parameters) {
            return $this->resolve($abstract, $parameters);
        })();
    }





    /**
     * $this->alias('view', View::class)
     * $this->alias(Renderer::class, View::class)
     *
     * @param $alias
     * @param $abstract
     * @return self
     */
    public function alias($alias, $abstract): self
    {
        $this->aliases[$alias] = $abstract;

        return $this;
    }




    /**
     * @param $abstract
     * @return mixed
     */
    public function getAlias($abstract)
    {
        if($this->hasAlias($abstract)) {
            return $this->aliases[$abstract];
        }

        return $abstract;
    }



    /**
     * @param $id
     * @return bool
     */
    public function has($id): bool
    {
        return $this->bound($id) || $this->hasInstance($id) || $this->hasAlias($id);
    }



    /**
     * @param $id
     * @return bool
     */
    public function hasInstance($id): bool
    {
        return isset($this->instances[$id]);
    }




    /**
     * @param $id
     * @return bool
     */
    public function hasAlias($id): bool
    {
        return isset($this->aliases[$id]);
    }



    /**
     * @param $id
     * @return bool
     */
    public function resolved($id): bool
    {
        return isset($this->resolved[$id]);
    }




    /**
     * @param $abstract
     * @return bool
     */
    protected function bounded($abstract): bool
    {
        return $this->bound($abstract) && isset($this->bindings[$abstract]['concrete']);
    }



    /**
     * @param $id
     * @return mixed|null
     */
    public function get($id)
    {
        return (function () use ($id) {

            try {

                return $this->resolve($id);

            } catch (Exception $e) {

                if ($this->has($id)) {
                    throw $e;
                }

                throw new ContainerException($e->getMessage(), $e->getCode());
            }

        })();
    }


    /**
     * @param string $abstract
     * @param array $parameters
     * @return mixed
     * @throws Exception
     */
    public function resolve(string $abstract, array $parameters = [])
    {
        // get abstract from alias
        $abstract = $this->getAlias($abstract);

        // get concrete context
        $concrete = $this->getConcreteContext($abstract);

        if($this->resolvable($concrete)) {
            $concrete = $this->resolveConcrete($concrete, $parameters);
            $this->resolved[$abstract] = true;
        }

        if($this->shared($abstract)) {
            return $this->makeSingleton($abstract, $concrete);
        }

        if (is_object($concrete) || is_string($concrete)) {
            return $concrete;
        }

        return null;
    }



    /**
     * get function dependencies
     *
     * @param array $dependencies
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function resolveDependencies(array $dependencies, array $params = []): array
    {
        $resolvedDependencies = [];

        foreach ($dependencies as $parameter) {

            $dependency = $parameter->getClass();

            if($parameter->isOptional()) { continue; }
            if($parameter->isArray()) { continue; }

            if(\is_null($dependency)) {
                $resolvedDependencies[] = $this->resolveParameters($parameter, $params);
            } else {
                $resolvedDependencies[] = $this->get($dependency->getName());
            }
        }

        return $resolvedDependencies;
    }





    /**
     * @param ReflectionParameter $parameter
     * @param array $params
     * @return mixed
     */
    protected function resolveParameters(ReflectionParameter $parameter, array $params)
    {
        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }else {
            if (\array_key_exists($parameter->getName(), $params)) {
                return $params[$parameter->getName()];
            } elseif($this->hasInstance($parameter->getName())) {
                return $parameter->getName();
            }else {
                $this->unresolvableDependencyException($parameter);
            }
        }
    }






    /**
     * @param ReflectionParameter $parameter
     * @return mixed
     */
    protected function unresolvableDependencyException(ReflectionParameter $parameter)
    {
        /* $name = $parameter->getName(); */
        $message = "Unresolvable dependency [{$parameter}] in class {$parameter->getDeclaringClass()->getName()}";

        throw new InvalidArgumentException($message);
    }






    /**
     * @param $concrete
     * @return bool
     */
    public function resolvable($concrete): bool
    {
        if($concrete instanceof Closure) {
            return true;
        }

        if (\is_string($concrete) && \class_exists($concrete)) {
            return true;
        }

        return false;
    }




    /**
     * @param $concrete
     * @param array $params
     * @return mixed
     * @throws Exception
     */
    public function resolveConcrete($concrete, array $params = [])
    {
        if($concrete instanceof Closure) {
            return $this->call($concrete, $params);
        }

        return $this->makeInstance($concrete, $params);
    }



    /**
     * @param string $concrete
     * @param array $params
     * @return object|null
     * @throws ReflectionException
     * @throws Exception
     */
    protected function makeInstance(string $concrete, array $params = [])
    {
        $reflectedClass = new ReflectionClass($concrete);

        if(! $reflectedClass->isInstantiable()) {
            throw new ReflectionException('Cannot instantiate given argument ('. $concrete .')');
        }

        $constructor = $reflectedClass->getConstructor();

        if(\is_null($constructor)) {
            return $reflectedClass->newInstance();
        }

        $dependencies = $this->resolveDependencies($constructor->getParameters(), $params);

        return $reflectedClass->newInstanceArgs($dependencies);
    }



    /**
     * @param $concrete
     * @param array $params
     * @param string $method
     * @return mixed
     */
    public function call($concrete, array $params = [], string $method = '')
    {
        if (is_callable($concrete)) {
            if ($concrete instanceof Closure) {
                return $this->callAnonymous($concrete, $params);
            }elseif (is_string($concrete)) {
                return call_user_func($concrete);
            }
        }elseif (\is_string($concrete) && $method) {
            return $this->callAction($concrete, $method, $params);
        }else{
            throw new InvalidArgumentException("callback argument '{$concrete}' is not callable.");
        }
    }


    /**
     * @param Closure $concrete
     * @param array $params
     * @return false|mixed
     */
    public function callAnonymous(Closure $concrete, array $params = [])
    {
        return (function () use ($concrete, $params) {

            $reflectedFunction  = new \ReflectionFunction($concrete);
            $functionParameters = $reflectedFunction->getParameters();
            $params = $this->resolveDependencies($functionParameters, $params);

            return call_user_func_array($concrete, $params);

        })();
    }




    /**
     * @param string $concrete
     * @param string $method
     * @param array $params
     * @return false|mixed
     */
    public function callAction(string $concrete, string $method, array $params = [])
    {
        return (function () use ($concrete, $method, $params) {

            try {

                $reflectedMethod = new \ReflectionMethod($concrete, $method);
                $arguments = $this->resolveDependencies($reflectedMethod->getParameters(), $params);

                $object = $this->get($concrete);

                $implements = class_implements($object);

                if (isset($implements[ContainerAwareInterface::class])) {
                    $object->setContainer($this);
                }

                return call_user_func_array([$object, $method], $arguments);

            } catch (Exception $e) {

                throw new ContainerException($e->getMessage());
            }

        })();
    }




    /**
     * @param $object
     * @param $method
     * @param array $params
     * @return false|mixed
     */
    public function callback($object, $method, array $params = [])
    {
        return call_user_func_array([$object, $method], $params);
    }





    /**
     * Flush the container of all bindings and resolved instances.
     *
     * @return void
     */
    public function flush()
    {
        $this->aliases = [];
        $this->resolved = [];
        $this->bindings = [];
        $this->instances = [];
    }




    /**
     * @param $abstract
     * @return bool
     */
    protected function isShared($abstract): bool
    {
        return isset($this->bindings[$abstract]['shared'])
            && ($this->bindings[$abstract]['shared'] === true);
    }




    /**
     * @param $concrete
     * @return object
     */
    protected function resolveGivenParameter($concrete)
    {
        return \is_string($concrete) ? $this->get($concrete) : $concrete;
    }





    /**
     * @param mixed $id
     * @return bool
     */
    public function offsetExists($id): bool
    {
        return $this->has($id);
    }





    /**
     * @param mixed $id
     * @return mixed
     * @throws Exception
     */
    public function offsetGet($id)
    {
        return $this->get($id);
    }





    /**
     * @param mixed $id
     * @param mixed $value
     */
    public function offsetSet($id, $value)
    {
        $this->bind($id, $value);
    }





    /**
     * @param mixed $id
     */
    public function offsetUnset($id)
    {
        unset($this->bindings[$id], $this->instances[$id], $this->resolved[$id]);
    }





    /**
     * @param $name
     * @return array|bool|mixed|object|string|null
     */
    public function __get($name)
    {
        return $this[$name];
    }




    /**
     * @param $name
     * @param $value
    */
    public function __set($name, $value)
    {
        $this[$name] = $value;
    }

}