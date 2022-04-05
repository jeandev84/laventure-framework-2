<?php
namespace Laventure\Component\Database\Query\Builders;


use Laventure\Component\Database\Query\Builders\Common\SqlBuilder;


/**
 * @DeleteBuilder
*/
class DeleteBuilder extends SqlBuilder
{

    /**
     * @param string $table
    */
    public function __construct(string $table)
    {
        $this->table = $table;
    }




    /**
     * @inheritDoc
    */
    protected function buildBeforeConditionSQL(): string
    {
        return sprintf("DELETE FROM %s", $this->table);
    }
}