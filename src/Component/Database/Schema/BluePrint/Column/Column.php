<?php
namespace Laventure\Component\Database\Schema\BluePrint\Column;




/**
 * @Column
*/
class Column implements \ArrayAccess
{


    /**
     * @var array
    */
    public $params = [
        'name'           => '',
        'type'           => '',
        'default'        => '',
        'primaryKey'     => '',
        'nullable'       => '',
        'collation'      => '',
        'comments'       => ''
    ];




    /**
     * Column constructor
     *
     * @param array $params
    */
    public function __construct(array $params)
    {
         $this->params  = array_merge($this->params, $params);
    }



    /**
     * Set nullable column
     *
     * @return $this
    */
    public function nullable($value = 'DEFAULT NULL'): self
    {
          return $this->with('nullable', $value);
    }




    /**
     * Add interphases
     *
     * Example $this->collation('utf8_unicode'),
     *
     * @param string $collation
     * @return $this
    */
    public function collation(string $collation): self
    {
         return $this->with('collation', $collation);
    }



    /**
     * Add comment to column
     *
     * @param $comment
     * @return $this
    */
    public function comments($comment): self
    {
        return $this->with('comments', $this->resolveComment($comment));
    }




    /**
     * @param $comment
     * @return string
    */
    protected function resolveComment($comment): string
    {
         return (is_array($comment) ? join(', ', $comment) : $comment);
    }




    /**
     * @return string
    */
    public function __toString()
    {
        return $this->printColumn();
    }



    /**
     * @return string
    */
    public function printColumn(): string
    {
         return trim(implode($this->filterParams()));
    }




    /**
     * @return array
    */
    private function filterParams(): array
    {
         $columns = [];

         foreach ($this->params as $constraint) {
             if (! is_null($constraint)) {
                 $columns[] = sprintf('%s ', $constraint);
             }
         }

         return $columns;
    }




    /**
     * Determine if given param name isset
     *
     * @param $name
     * @return bool
    */
    public function has($name): bool
    {
         return isset($this->params[$name]);
    }




    /**
     * Get given param value
     *
     * @param $name
     * @param $default
     * @return mixed|string|null
    */
    public function get($name, $default = null)
    {
         return $this->params[$name] ?? $default;
    }




    /**
     * Remove the given name param
     *
     * @param $name
     * @return void
    */
    public function remove($name)
    {
          unset($this->params[$name]);
    }



    /**
     * Set value given param
     *
     * @param $name
     * @param $value
     * @return $this
    */
    public function with($name, $value): self
    {
         $this->params[$name] = $value;

         return $this;
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
          return $this->with($offset, $value);
    }



    /**
     * @inheritDoc
    */
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }
}