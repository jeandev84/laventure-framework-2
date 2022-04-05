<?php
namespace Laventure\Component\Database\Query\Drivers;


use Laventure\Component\Database\Connection\Contract\ConnectionInterface;
use Laventure\Component\Database\Query\Builders\Common\SqlBuilder;
use Laventure\Component\Database\Query\Builders\DeleteBuilder;
use Laventure\Component\Database\Query\Builders\InsertBuilder;
use Laventure\Component\Database\Query\Builders\SelectBuilder;
use Laventure\Component\Database\Query\Builders\UpdateBuilder;
use Laventure\Component\Database\Query\Contract\SqlQueryBuilderInterface;


/**
 * @AbstractSqlQueryBuilder
*/
abstract class AbstractSqlQueryBuilder implements SqlQueryBuilderInterface
{


    /**
     * @var ConnectionInterface
    */
    protected $connection;





    /**
     * @param ConnectionInterface $connection
    */
    public function __construct(ConnectionInterface $connection)
    {
           $this->connection = $connection;
    }



    /**
     * @inheritDoc
    */
    public function select(array $selects, string $table): SelectBuilder
    {
         return $this->connectSQLBuilder(new SelectBuilder($selects, $table));
    }




    /**
     * @inheritDoc
    */
    abstract public function insert(array $attributes, string $table): InsertBuilder;





    /**
     * @inheritDoc
    */
    abstract public function update(array $attributes, string $table): UpdateBuilder;






    /**
     * @inheritDoc
    */
    public function delete($table): DeleteBuilder
    {
         return $this->connectSQLBuilder(new DeleteBuilder($table));
    }



    /**
     * @param SqlBuilder $builder
     * @return mixed
    */
    protected function connectSQLBuilder(SqlBuilder $builder)
    {
        $builder->setConnection($this->connection);

        return $builder;
    }
}