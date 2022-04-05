<?php
namespace Laventure\Component\Database\Query\Drivers\PDO\Common;


use Laventure\Component\Database\Query\Builders\DeleteBuilder;
use Laventure\Component\Database\Query\Builders\SelectBuilder;
use Laventure\Component\Database\Query\Drivers\AbstractSqlQueryBuilder;
use Laventure\Component\Database\Query\Drivers\PDO\SQL\Delete;
use Laventure\Component\Database\Query\Drivers\PDO\SQL\Insert;
use Laventure\Component\Database\Query\Drivers\PDO\SQL\Select;
use Laventure\Component\Database\Query\Drivers\PDO\SQL\Update;
use Laventure\Component\Database\Query\Builders\InsertBuilder;
use Laventure\Component\Database\Query\Builders\UpdateBuilder;


/**
 * @PdoQueryBuilder
*/
class PdoQueryBuilder extends AbstractSqlQueryBuilder
{


    /**
     * @param array $selects
     * @param string $table
     * @return SelectBuilder
    */
    public function select(array $selects, string $table): SelectBuilder
    {
        return $this->connectSQLBuilder(new Select($selects, $table));
    }



    /**
     * @inheritDoc
    */
    public function insert(array $attributes, string $table): InsertBuilder
    {
         return $this->connectSQLBuilder(new Insert($attributes, $table));
    }



    /**
     * @inheritDoc
    */
    public function update(array $attributes, string $table): UpdateBuilder
    {
        return $this->connectSQLBuilder(new Update($attributes, $table));
    }




    /**
     * @inheritDoc
    */
    public function delete($table): DeleteBuilder
    {
         return $this->connectSQLBuilder(new Delete($table));
    }
}