<?php
namespace Laventure\Component\Database\Connection\Configuration;



/**
 * @ConfigurationBag
 */
class ConfigurationBag implements \ArrayAccess
{


    /**
     * @var array
    */
    protected $params = [];




    /**
     * @param array $params
    */
    public function __construct(array $params = [])
    {
          if ($params) {
              $this->merge($params);
          }
    }




    /**
     * @param $name
     * @param $config
     * @return self
    */
    public function add($name, $config): self
    {
        $this->params[$name] = $config;

        return $this;
    }




    /**
     * @param array $params
     * @return self
    */
    public function merge(array $params): self
    {
        $this->params = array_merge($this->params, $params);

        return $this;
    }





    /**
     * @param $name
     * @param null $default
     * @return mixed
    */
    public function get($name, $default = null)
    {
         return $this->params[$name] ?? $default;
    }





    /**
     * @param $name
     * @return bool
    */
    public function has($name): bool
    {
        return isset($this->params[$name]);
    }




    /**
     * @param $name
     * @return void
    */
    public function remove($name)
    {
        unset($this->params[$name]);
    }




    /**
     * @return array
    */
    public function all(): array
    {
        return $this->params;
    }





    /**
     * @inheritDoc
    */
    public function offsetExists($offset): bool
    {
        return $this->has($offset);
    }



    /**
     * @inheritDoc
    */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }



    /**
     * @inheritDoc
    */
    public function offsetSet($offset, $value)
    {
         $this->add($offset, $value);
    }



    /**
     * @inheritDoc
    */
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }
}