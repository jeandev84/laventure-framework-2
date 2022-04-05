<?php
namespace Laventure\Component\Database\Query\Builders;



use Laventure\Component\Database\Query\Builders\Common\SqlBuilder;


/**
 * @ExpressionBuilder
*/
class ExpressionBuilder
{


    /**
     * @var SqlBuilder
    */
    protected $builder;




    /**
     * @param SqlBuilder $builder
    */
    public function __construct(SqlBuilder $builder)
    {
         $this->builder = $builder;
    }
}