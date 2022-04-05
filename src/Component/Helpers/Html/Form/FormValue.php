<?php
namespace Laventure\Component\Helpers\Html\Form;


use ArrayAccess;


/**
 * @FormValue
*/
class FormValue implements ArrayAccess
{

      /**
       * @var
      */
      protected $data;




      /**
        * FormValue constructor
        *
        * @param $data
      */
      public function __construct($data)
      {
            $this->data = $data;
      }




      /**
        * @param string $name
        * @param null $default
        * @return mixed|null
      */
      public function get(string $name, $default = null)
      {
           return $this->data[$name] ?? $default;
      }



      /**
       * @return array
      */
      public function all(): array
      {
           return $this->data;
      }



      /**
       * @inheritDoc
      */
      public function offsetExists($offset)
      {
           return isset($this->data[$offset]);
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
            $this->data[$offset] = $value;
      }



      /**
       * @inheritDoc
      */
      public function offsetUnset($offset)
      {
           unset($this->data[$offset]);
      }
}