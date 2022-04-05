<?php
namespace Laventure\Component\Database\Query\Drivers\PDO\SQL;

use Laventure\Component\Database\Query\Builders\InsertBuilder;


/**
 * @Insert
*/
class Insert extends InsertBuilder
{

    /**
     * @return array
    */
    protected function getAttributes(): array
    {
        return array_keys($this->attributes);
    }



    /**
     * @return string
    */
    protected function getAttributeToInline(): string
    {
        return  implode(', ', $this->getAttributes());
    }




    /**
     * @return string
    */
    protected function getBindParametersToInline(): string
    {
        return  ":" . implode(", :", $this->getAttributes());
    }




    /**
     * @inheritDoc
    */
    protected function buildBeforeConditionSQL(): string
    {
        return sprintf("INSERT INTO %s (%s) VALUES (%s)",
            $this->table,
            $this->getAttributeToInline(),
            $this->getBindParametersToInline()
        );
    }



    /**
     * @return string
    */
    protected function buildConditionSQL(): string
    {
        return "";
    }


    protected function buildAfterConditionSQL(): string
    {
        return "";
    }
}