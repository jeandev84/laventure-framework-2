<?php
namespace Laventure\Component\Database\Query\Drivers\PDO\SQL;


use Laventure\Component\Database\Query\Builders\UpdateBuilder;
use Laventure\Component\Database\Query\Drivers\PDO\Common\PdoConditionBuilderTrait;


/**
 * @Update
*/
class Update extends UpdateBuilder
{


    use PdoConditionBuilderTrait;


    /**
     * @inheritDoc
    */
    protected function buildBeforeConditionSQL(): string
    {
        return sprintf("UPDATE %s SET %s",
            $this->table,
            $this->buildColumnAssigns()
        );
    }




    /**
     * @return string
    */
    protected function buildColumnAssigns(): string
    {
        $fields = [];

        foreach ($this->attributes as $column) {
            $fields[] = sprintf("%s = :%s", $column, $column);
            /* array_push($fields, sprintf("%s = :%s", $column, $column)); */
        }

        return join(', ', $fields);
    }




    /**
     * @return string
    */
    protected function buildAfterConditionSQL(): string
    {
        return "";
    }
}