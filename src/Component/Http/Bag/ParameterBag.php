<?php
namespace Laventure\Component\Http\Bag;


use Laventure\Component\Http\Bag\Contract\ParameterBagInterface;



/**
 * @ParameterBag
*/
class ParameterBag implements ParameterBagInterface
{

       /**
        * @var array
       */
       protected $params = [];




       /**
         * ParameterBag constructor
         *
         * @param array $params
       */
       public function __construct(array $params = [])
       {
            $this->params = $params;
       }



       /**
        * Set parameter in the bag
        *
        * @param string $key
        * @param $value
        * @return $this
       */
       public function set(string $key, $value): self
       {
            $this->params[$key] = $value;

            return $this;
       }



        /**
         * @inheritDoc
        */
        public function has($key): bool
        {
            return \array_key_exists($key, $this->params);
        }



        /**
         * @inheritDoc
        */
        public function get(string $key, $default = null)
        {
            return $this->params[$key] ?? $default;
        }



       /**
        * @return array
       */
       public function all(): array
       {
           return $this->params;
       }



       /**
        * @param array $params
      */
      public function merge(array $params)
      {
          $this->params = array_merge($this->params, $params);
      }



      /**
       * @param string $key
       * @return void
      */
      public function remove(string $key)
      {
          unset($this->params[$key]);
      }



      /**
       * @param $key
       * @param $value
       * @return void
      */
      public function parse($key, $value = null)
      {
          $data = \is_array($key) ? $key : [$key => $value];

          $this->merge($data);
      }



      /**
       * @return void
      */
      public function clear()
      {
          $this->params = [];
      }



     /**
      * @param $key
      * @param int $default
      * @return int
     */
     public function getInt($key, int $default = 0): int
     {
        return (int) $this->get($key, $default);
     }




     /**
      * @return false|string
     */
      public function toStringify()
      {
            return ''; // \json_encode($this->params);
      }



     /**
      * @param int $type
      * @return array
     */
     public function filterParams(int $type): array
     {
        $body = [];
        foreach (array_keys($this->all()) as $key) {
            $body[$key] = filter_input($type, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        }

        return $body;
     }
}