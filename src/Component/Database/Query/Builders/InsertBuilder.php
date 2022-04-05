<?php
namespace Laventure\Component\Database\Query\Builders;


use Laventure\Component\Database\Query\Builders\Common\SqlBuilder;


/**
 * @InsertBuilder
*/
class InsertBuilder extends SqlBuilder
{

    /**
     * @var array
    */
    protected $attributes = [];




    /**
     * @param array $attributes
     * @param string $table
    */
    public function __construct(array $attributes, string $table)
    {
        $this->setParameters($attributes);
        $this->attributes = $attributes;
        $this->table = $table;
    }
}