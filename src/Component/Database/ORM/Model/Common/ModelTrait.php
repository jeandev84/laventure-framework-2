<?php
namespace Laventure\Component\Database\ORM\Model\Common;


/**
 * @ModelTrait
*/
trait ModelTrait
{

    /**
     * Model attributes
     *
     * @var array
    */
    protected $attributes = [];




    /**
     * Model columns where we can insert or update data
     *
     * @var array
     */
    protected $insertable = [];




    /**
     * Model keep column
     *
     * @var string[]
    */
    protected $guarded = ['id'];




    /**
     * Set model attribute
     *
     * @param string $column
     * @param $value
     * @return void
     */
    public function setAttribute(string $column, $value)
    {
        $this->attributes[$column] = $value;
    }



    /**
     * Set attributes
     *
     * @param array $attributes
     * @return void
     */
    public function setAttributes(array $attributes)
    {
        foreach ($attributes as $column => $value) {
            $this->setAttribute($column, $value);
        }
    }




    /**
     * @param string $column
     * @return bool
     */
    public function hasAttribute(string $column): bool
    {
        return isset($this->attributes[$column]);
    }



    /**
     * Remove attribute
     *
     * @param string $column
     * @return void
     */
    public function removeAttribute(string $column)
    {
        unset($this->attributes[$column]);
    }




    /**
     * Get attribute
     *
     * @param string $column
     * @return mixed|null
    */
    public function getAttribute(string $column)
    {
        return $this->attributes[$column] ?? null;
    }

}