<?php
namespace Laventure\Component\Database\Connection\Drivers\PDO\Statement;



use Laventure\Component\Database\Connection\Contract\QueryInterface;
use PDO;
use PDOException;
use PDOStatement;


/**
 * @Query
*/
class Query implements QueryInterface
{


    /**
     * Connection
     *
     * @var PDO
    */
    protected $pdo;



    /**
     * Query
     *
     * @var string
    */
    protected $sql;




    /**
     * Bind params
     *
     * @var array
    */
    protected $params = [];




    /**
     * Bind values
     *
     * @var array
    */
    protected $bindValues = [];




    /**
     * Fetch mode
     *
     * @var int
    */
    protected $fetchMode = PDO::FETCH_OBJ;




    /**
     * PDO statement
     *
     * @var PDOStatement
    */
    protected $statement;




    /**
     * Entity class
     *
     * @var string
    */
    protected $entityClass;




    /**
     * Query log
     *
     * @var array
    */
    protected $queryLogs = [];





    /**
     * @param PDO $pdo
    */
    public function __construct(PDO $pdo)
    {
           $this->pdo = $pdo;
    }



    /**
     * @inheritDoc
    */
    public function prepare($sql, array $params = []): self
    {
         $this->statement = $this->pdo->prepare($sql);

         $this->sql    = $sql;
         $this->params = $params;

         return $this;
    }




    /**
     * @inheritDoc
    */
    public function with($entityClass): self
    {
        $this->entityClass = $entityClass;

        return $this;
    }





    /**
     * Set fetch mode
     *
     * @param mixed $fetchMode
     * @return $this
    */
    public function fetchMode($fetchMode): self
    {
        $this->fetchMode = (int) $fetchMode;

        return $this;
    }



    /**
     * Example:
     *
     *  bind(':name', 'John')
     *
     * @param string $param
     * @param $value
     * @param int $type
     * @return $this
    */
    public function bind(string $param, $value, int $type = 0): self
    {
        if ($type === 0) {

            $typeName = strtolower(gettype($type));

            switch ($typeName) {
                case 'integer':
                    $type = PDO::PARAM_INT;
                    break;
                case 'boolean':
                    $type = PDO::PARAM_BOOL;
                    break;
                case 'null':
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
                    break;
            }
        }

        $this->bindValues[] = [$param, $value, $type];

        return $this;
    }



    /**
     * @inheritDoc
    */
    public function execute(): bool
    {
        try {

            if ($this->bindValues) {
                return $this->executeQueryBindParams($this->bindValues);
            }

            if ($this->statement()->execute($this->params)) {
                $this->queryLogs[$this->sql] = $this->params;
                return true;
            }

        } catch (PDOException $e) {

            trigger_error($e->getMessage());
        }

        return false;
    }



    /**
     * Execute query bind params
     *
     * @param array $bindValues
     * @return bool
    */
    protected function executeQueryBindParams(array $bindValues): bool
    {
        $params = [];

        foreach ($bindValues as $bindValue) {
            list($param, $value, $type) = $bindValue;
            $this->statement->bindValue($param, $value, $type);
            $params[$param] = $value;
        }


        if ($this->statement->execute()) {
             $this->queryLogs[$this->sql] = $params;
             return true;
        }

        return false;
    }




    /**
     * @inheritDoc
    */
    public function getResult()
    {
        $this->execute();

        if ($this->entityClass) {

            $this->statement->setFetchMode(PDO::FETCH_CLASS, $this->entityClass);

            return $this->statement->fetchAll();
        }

        return $this->statement()->fetchAll($this->fetchMode);
    }




    /**
     * @inheritDoc
    */
    public function getOneOrNullResult()
    {
        $this->execute();

        if($this->entityClass) {
            return $this->statement->fetchObject($this->entityClass);
        }

        return $this->statement->fetch($this->fetchMode);
    }




    /**
     * @inheritDoc
    */
    public function getArrayColumns()
    {
        $this->execute();

        return $this->statement->fetchAll(PDO::FETCH_COLUMN);
    }




    /**
     * @inheritDoc
    */
    public function getFirstResult()
    {
        $this->execute();

        return $this->getResult()[0] ?? null;
    }





    /**
     * Get numb rows
     * 
     * @inheritDoc
    */
    public function getSingleScalarResult()
    {
        return $this->statement()->rowCount();
    }




    /**
     * Get error info
     * @inheritDoc
    */
    public function getErrorInfo()
    {
         return $this->statement()->errorInfo();
    }




    /**
     * @return array
    */
    public function getQueryLogs(): array
    {
         return $this->queryLogs;
    }




    /**
     * @return PDOStatement
    */
    private function statement(): PDOStatement
    {
        if (! $this->statement instanceof PDOStatement) {
            trigger_error("Invalid query statement.");
        }

        return $this->statement;
    }
}