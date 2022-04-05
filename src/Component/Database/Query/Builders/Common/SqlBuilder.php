<?php
namespace Laventure\Component\Database\Query\Builders\Common;



use Laventure\Component\Database\Connection\Contract\ConnectionInterface;
use Laventure\Component\Database\Query\Builders\ExpressionBuilder;
use Laventure\Component\Database\Query\Query;


/**
 * @SqlBuilder
*/
abstract class SqlBuilder
{

    /**
     * Connection
     *
     * @var ConnectionInterface
    */
    protected $connection;




    /**
     * Entity class name
     *
     * @var string
    */
    protected $entityClass;




    /**
     * @var string
    */
    protected $table;




    /**
     * @var string
    */
    protected $alias;



    /**
     * @var array
    */
    protected $where = [];




    /**
     * @var array
    */
    protected $sets = [];





    /**
     * @var array
     */
    protected $parameters = [];




    /**
     * @param ConnectionInterface $connection
     * @return void
    */
    public function setConnection(ConnectionInterface $connection)
    {
           $this->connection = $connection;
    }




    /**
     * @param $entityClass
     * @return void
    */
    public function setEntityClass($entityClass)
    {
         $this->entityClass = $entityClass;
    }




    /**
     * @return ConnectionInterface
    */
    public function getConnection(): ConnectionInterface
    {
        return $this->connection;
    }



    /**
     * @param string $table
     * @param string $alias
     * @return $this
    */
    public function table(string $table, string $alias = ''): self
    {
        $this->table = $table;
        $this->alias = $alias;

        return $this;
    }




    /**
     * @param $table
     * @param string $alias
     * @return string
    */
    public function as($table, string $alias = ''): string
    {
         return $this->table = $alias ? sprintf('%s %s', $table, $alias) : $table;
    }




    /**
     * @return array
    */
    public function getConditions(): array
    {
        return $this->where;
    }




    /**
     * @param array $conditions
     * @return $this
    */
    public function addConditions(array $conditions): self {
         foreach ($conditions as $condition) {
             $this->andWhere($condition);
         }

         return $this;
    }





    /**
     * @param string $condition
     * @return $this
    */
    public function where(string $condition): self
    {
        return $this->andWhere($condition);
    }





    /**
     * @param string $condition
     * @return self
    */
    public function andWhere(string $condition): self
    {
        $this->where["AND"][] = $condition;

        return $this;
    }




    /**
     * @param string $condition
     * @return $this
    */
    public function orWhere(string $condition): self
    {
        $this->where["OR"][] = $condition;

        return $this;
    }




    /**
     * @param string $condition
     * @return self
    */
    public function notWhere(string $condition): self
    {
        return $this->andWhere("NOT $condition");
    }



    /**
     * @param string $pattern
     * @return $this
     */
    public function whereLike(string $pattern): self
    {
        return $this->andWhere("LIKE $pattern");
    }



    /**
     * @param $column
     * @param mixed $first
     * @param mixed $end
     * @return $this
    */
    public function whereBetween($column, $first, $end): self
    {
        return $this->andWhere("$column BETWEEN $first AND $end");
    }





    /**
     * @param mixed $first
     * @param mixed $end
     * @return $this
    */
    public function whereNotBetween($column, $first, $end): self
    {
        return $this->andWhere("$column NOT BETWEEN $first AND $end");
    }




    /**
     * @param $column
     * @param array $data
     * @return $this
    */
    public function whereIn($column, array $data): self
    {
        $printSQL = sprintf("%s IN (%s)", $column, implode(', ', $data));

        return $this->andWhere($printSQL);
    }



    /**
     * @param $column
     * @param array $data
     * @return $this
     */
    public function whereNotIn($column, array $data): self
    {
        $printSQL = sprintf("%s NOT IN (%s)", $column, implode(', ', $data));

        return $this->andWhere($printSQL);
    }




    /**
     * @param $key
     * @param $value
     * @return $this
    */
    public function set($key, $value): self
    {
        $this->sets[$key] = $value;

        return $this;
    }




    /**
     * @return ExpressionBuilder
    */
    public function expr(): ExpressionBuilder
    {
        return new ExpressionBuilder($this);
    }




    /**
     * @param $key
     * @param $value
     * @return $this
    */
    public function setParameter($key, $value): self
    {
        $this->parameters[$key] = $value;

        return $this;
    }




    /**
     * @param array $parameters
     * @return self
    */
    public function setParameters(array $parameters): self
    {
        foreach ($parameters as $key => $value) {
            $this->setParameter($key, $value);
        }

        return $this;
    }




    /**
     * @return void
    */
    protected function buildBeforeConditionSQL(): string
    {
        trigger_error("Method ". __METHOD__ . " must be implements inside ". get_called_class());
    }



    /**
     * @return string
    */
    protected function buildConditionSQL(): string
    {
        $wheres = $this->getConditions();

        if (! empty($wheres)) {

            $sql  = ' WHERE ';
            $key = key($wheres);

            foreach ($wheres as $command => $conditions) {

                $prefix = '';

                if ($key !== $command) {
                    $prefix = $command;
                }

                $sql .= $this->buildConditions($command, $conditions, $prefix);
            }

            return $sql;

        }

        return '';
    }


    /**
     * @param string $command
     * @param array $conditions
     * @param string $prefix
     * @return string
    */
    private function buildConditions(string $command, array $conditions, string $prefix = ''): string
    {
         return ($prefix ? " ". $prefix . " " : ''  ) . $this->buildParts($command, $conditions);
    }


    /**
     * @param string $command
     * @param array $conditions
     * @return string
    */
    private function buildParts(string $command, array $conditions): string
    {
        return implode( " ". $command . " ", $conditions);
    }




    /**
     * @return string
    */
    protected function buildAfterConditionSQL(): string
    {
        return '';
    }


    /**
     * @return string
    */
    public function getTable(): string
    {
        return $this->table;
    }




    /**
     * @return string
    */
    public function getSQL(): string
    {
        $sql = $this->buildBeforeConditionSQL();

        if ($sqlCondition = $this->buildConditionSQL()) {
            $sql .= $sqlCondition;
        }

        if ($sqlAfterCondition = $this->buildAfterConditionSQL()) {
            $sql .= $sqlAfterCondition;
        }

        return trim($sql, ' ') .';';

    }



    /**
     * @return array
    */
    public function getParameters(): array
    {
        return $this->parameters;
    }



    /**
     * Get query
     *
     * @return Query
    */
    public function getQuery(): Query
    {
        $query = $this->getConnection()->query($this->getSQL(), $this->getParameters());

        $query->with($this->entityClass);

        return new Query($query, $this->connection);
    }




    /**
     * @return mixed
    */
    public function execute()
    {
        return $this->getQuery()->execute();
    }
}