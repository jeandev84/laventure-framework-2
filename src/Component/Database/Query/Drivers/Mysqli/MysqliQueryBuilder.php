<?php
namespace Laventure\Component\Database\Query\Drivers\Mysqli;


use Laventure\Component\Database\Query\Drivers\AbstractSqlQueryBuilder;
use Laventure\Component\Database\Query\Builders\InsertBuilder;
use Laventure\Component\Database\Query\Builders\UpdateBuilder;


/**
 * @QueryBuilder
*/
class MysqliQueryBuilder extends AbstractSqlQueryBuilder
{

    /**
     * @inheritDoc
    */
    public function insert(array $attributes, string $table): InsertBuilder
    {
        // TODO: Implement insert() method.
    }

    /**
     * @inheritDoc
     */
    public function update(array $attributes, string $table): UpdateBuilder
    {
        // TODO: Implement update() method.
    }
}