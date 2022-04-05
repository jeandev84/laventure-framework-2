<?php
namespace Laventure\Component\Database\Schema\BluePrint\Column;



/**
 * @ColumnCollection
*/
class ColumnCollection
{

    /**
     * @var Column[]
     */
    protected $columns = [];





    /**
     * Add column
     *
     * @param $name
     * @param Column $column
     * @return Column
    */
    public function addColumn($name, Column $column): Column
    {
        $this->columns[$name] = $column;

        return $column;
    }




    /**
     * Determine if the given name isset
     *
     * @param $name
     * @return bool
    */
    public function hasColumn($name): bool
    {
        return isset($this->columns[$name]);
    }



    /**
     * Get column value
     *
     * @param $name
     * @return Column|null
    */
    public function getColumn($name): ?Column
    {
        return $this->columns[$name] ?? null;
    }




    /**
     * Remove column by given name
     *
     * @param $name
     * @return void
    */
    public function removeColumn($name)
    {
        unset($this->columns[$name]);
    }




    /**
     * Determine if empty columns
     *
     * @return bool
    */
    public function isEmpty(): bool
    {
        return empty($this->columns);
    }




    /**
     * Get columns count
     *
     * @return int
    */
    public function count(): int
    {
        return count($this->columns);
    }





    /**
     * Get all column
     *
     * @return Column[]
    */
    public function getColumns(): array
    {
        return $this->columns;
    }





    /**
     * @return string
    */
    public function getPrintedColumns(): string
    {
         $columns = array_values($this->getColumns());

         return implode(', ', $columns);
    }
}