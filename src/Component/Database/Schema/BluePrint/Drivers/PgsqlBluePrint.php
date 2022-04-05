<?php
namespace Laventure\Component\Database\Schema\BluePrint\Drivers;


use Laventure\Component\Database\Connection\Contract\ConnectionInterface;
use Laventure\Component\Database\Connection\Drivers\PDO\Connectors\PgsqlConnection;
use Laventure\Component\Database\Schema\BluePrint\BluePrint;
use Laventure\Component\Database\Schema\BluePrint\Column\Column;


/**
 * @PgsqlBluePrint
*/
class PgsqlBluePrint extends BluePrint
{

    /**
     * PgsqlBlueprint constructor
     *
     * @param PgsqlConnection $connection
     * @param string $table
    */
    public function __construct(ConnectionInterface $connection, string $table)
    {
         parent::__construct($connection, $table);
    }



    /**
     * @inheritDoc
    */
    public function increments(string $name): Column
    {
        return $this->add($name, 'SERIAL', null, '', true);
    }



    /**
     * @inheritDoc
    */
    public function boolean($name): Column
    {
        return $this->add($name, 'BOOLEAN', null);
    }



    /**
     * @param $name
     * @param int $length
     * @return Column
    */
    public function integer($name, int $length = 11): Column
    {
        return $this->add($name, 'INTEGER', null);
    }




    /**
     * @param $name
     * @return Column
    */
    public function datetime($name): Column
    {
        return $this->add($name, 'TIMESTAMP', null);
    }





    /**
     * @inheritDoc
    */
    public function createTable()
    {
        $sql = sprintf("CREATE TABLE IF NOT EXISTS %s (%s);",
            $this->getTable(), $this->getPrintedColumns()
        );

        return $this->connection->exec($sql);
    }



    /**
     * @inheritDoc
    */
    public function dropTable()
    {
        $sql= sprintf('DROP TABLE %s;', $this->getTable());

        return $this->connection->exec($sql);
    }




    /**
     * @inheritDoc
    */
    public function dropTableIfExists()
    {
        $sql = sprintf('DROP TABLE IF EXISTS %s;', $this->getTable());

        return $this->connection->exec($sql);
    }




    /**
     * @inheritDoc
    */
    public function truncateTable()
    {
        $sql = sprintf('TRUNCATE TABLE %s;', $this->getTable());

        return $this->connection->exec($sql);
    }





    /**
     * @inheritDoc
    */
    public function truncateCascade() {}






    /**
     * @inheritDoc
    */
    public function showTableColumns()
    {
        $schemaParams = $this->transformSchemaInformationToArray();

        return $schemaParams['column_name'];
    }





    /**
     * @inheritDoc
    */
    public function describeTable()
    {
        $this->connection->exec($this->describeTableSQLFunction());

        $sql = sprintf("select  *  from describe_table('%s')", $this->getTable());

        return $this->connection->query($sql)
                                ->fetchMode(\PDO::FETCH_ASSOC)
                                ->getResult();
    }




    /**
     * @return array
    */
    protected function transformSchemaInformationToArray(): array
    {
        $schemaInfos = $this->describeTable();

        $informationParams = [];

        foreach ($schemaInfos as $params) {
            $params = (array) $params;
            foreach ($params as $key => $value) {
                $informationParams[$key][] = $value;
            }
        }

        return $informationParams;
    }



    /**
     * @return string
    */
    protected function describeTableSQLFunction(): string
    {
        return "create or replace function describe_table(tbl_name text) 
                returns table(column_name varchar, data_type varchar,character_maximum_length int) as $$
                select column_name, data_type, character_maximum_length
                from INFORMATION_SCHEMA.COLUMNS where table_name = $1;
                $$
                language 'sql';
            ";
    }


}