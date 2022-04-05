<?php
namespace Laventure\Component\Database\Schema\BluePrint\Drivers;


use Laventure\Component\Database\Connection\Contract\ConnectionInterface;
use Laventure\Component\Database\Connection\Drivers\PDO\Connectors\MysqlConnection;
use Laventure\Component\Database\Schema\BluePrint\BluePrint;
use Laventure\Component\Database\Schema\BluePrint\Column\Column;


/**
 * @MysqlBluePrint
*/
class MysqlBluePrint extends BluePrint
{


    /**
     * MysqlBlueprint constructor
     *
     * @param MysqlConnection $connection
     * @param string $table
    */
    public function __construct(ConnectionInterface $connection, string $table)
    {
        parent::__construct($connection, $table);
    }



    public function boolean($name): Column
    {
        // TODO: Implement boolean() method.
    }

    /**
     * @inheritDoc
     */
    public function increments(string $name)
    {
        // TODO: Implement increments() method.
    }

    /**
     * @inheritDoc
     */
    public function createTable()
    {
        // TODO: Implement createTable() method.
    }

    /**
     * @inheritDoc
     */
    public function dropTable()
    {
        // TODO: Implement dropTable() method.
    }

    /**
     * @inheritDoc
     */
    public function dropTableIfExists()
    {
        // TODO: Implement dropTableIfExists() method.
    }

    /**
     * @inheritDoc
     */
    public function truncateTable()
    {
        // TODO: Implement truncateTable() method.
    }

    /**
     * @inheritDoc
     */
    public function truncateCascade()
    {
        // TODO: Implement truncateCascade() method.
    }



    /**
     * @inheritDoc
     */
    public function showTableColumns()
    {
        // TODO: Implement showTableColumns() method.
    }

    /**
     * @inheritDoc
     */
    public function describeTable()
    {
        // TODO: Implement describeTable() method.
    }
}