<?php
namespace Laventure\Component\Database\Schema\BluePrint;


use Laventure\Component\Database\Connection\Contract\ConnectionInterface;
use Laventure\Component\Database\Schema\BluePrint\Column\Column;
use Laventure\Component\Database\Schema\BluePrint\Column\ColumnCollection;
use Laventure\Component\Database\Schema\BluePrint\Drivers\Contract\BluePrintInterface;


/**
 * @BluePrint
*/
abstract class BluePrint implements BluePrintInterface
{



    /**
     * Table name
     *
     * @var string
    */
    protected $table;




    /**
     * @var ConnectionInterface
    */
    protected $connection;




    /**
     * @var ColumnCollection
    */
    protected $columns;




    /**
     * BluePrint constructor
     *
     * @param ConnectionInterface|null $connection
     * @param string|null $table
    */
    public function __construct(ConnectionInterface $connection, string $table)
    {
           $this->connection = $connection;
           $this->columns    = new ColumnCollection();
           $this->table = $table;
    }




    /**
     * @param string $table
     * @return void
    */
    public function with(string $table): self
    {
        $this->table = $table;

        return $this;
    }



    /**
     * @param $name
     * @param $type
     * @param int $length
     * @param null $default
     * @param mixed $autoincrement
     * @return Column
    */
    public function add($name, $type, $length = 11, $default = null, $autoincrement = null): Column
    {
          $type    = $this->getTypeFormat($type, $length);
          $default = $this->getDefaultFormat($default);

          $column = new Column(
              compact('name', 'type', 'default')
          );


          if ($autoincrement) {
               $column->with('primaryKey', 'PRIMARY KEY');
               if (is_string($autoincrement)) {
                    $column->with('autoincrement', $autoincrement);
               }
          }

          return $this->columns->addColumn($name, $column);
    }




    /**
     * Add integer column
     *
     * @param $name
     * @param int $length
     * @return Column
    */
    public function integer($name, int $length = 11): Column
    {
          return $this->add($name, 'INTEGER', $length);
    }



    /**
     * Add column type string
     *
     * @param string $name
     * @param int $length
     * @return Column
    */
    public function string(string $name, int $length = 255): Column
    {
        return $this->add($name, 'VARCHAR', $length);
    }




    /**
     * Add column type text.
     *
     * @param $name
     * @return Column
    */
    public function text($name): Column
    {
        return $this->add($name, 'TEXT', null);
    }




    /**
     * Add column type datetime
     *
     * @param $name
     * @return Column
    */
    public function datetime($name): Column
    {
        return $this->add($name, 'DATETIME', null);
    }




    /**
     * Add timestamp columns created_at, updated_at
     *
     * @return void
    */
    public function timestamps()
    {
        $this->datetime('created_at');
        $this->datetime('updated_at');
    }




    /**
     * Add soft delete column boolean
     *
     * @param bool $status
     * @return Column|void
    */
    public function softDeletes(bool $status = false): Column
    {
        if($status) {
            return $this->boolean('deleted_at');
        }
    }




    /**
     * @return mixed
    */
    protected function showTables()
    {
        return $this->connection->showTables();
    }



    /**
     * Add column type boolean
     *
     * @param $name
     * @return Column
    */
    abstract public function boolean($name): Column;






    /**
     * @return string
    */
    public function getTable(): string
    {
        return $this->connection->getRealTableName($this->table);
    }




    /**
     * Get connection
     *
     * @return ConnectionInterface
    */
    public function getConnection(): ConnectionInterface
    {
        return $this->connection;
    }




    /**
     * @inheritDoc
    */
    public function getColumns()
    {
        return $this->columns->getColumns();
    }



    /**
     * @return string
    */
    public function getPrintedColumns(): string
    {
         return $this->columns->getPrintedColumns();
    }


    /**
     * @param $type
     * @param $length
     * @return string
    */
    protected function getTypeFormat($type, $length): string
    {
        return $length ? sprintf('%s(%s)', $type, $length) : $type;
    }




    /**
     * @param $default
     * @return string
    */
    protected function getDefaultFormat($default): string
    {
        return $default ? sprintf('DEFAULT "%s"', $default) : 'NOT NULL';
    }




    /**
     * Add column increments
     *
     * @param string $name
     * @return
    */
    abstract public function increments(string $name);




    /**
     * @inheritDoc
    */
    abstract public function createTable();
}