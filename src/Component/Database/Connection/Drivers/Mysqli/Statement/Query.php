<?php
namespace Laventure\Component\Database\Connection\Drivers\Mysqli\Statement;


use Laventure\Component\Database\Connection\Contract\QueryInterface;



/**
 * @Query
*/
class Query implements QueryInterface
{

    /**
     * @inheritDoc
     */
    public function prepare($sql, array $params = [])
    {
        // TODO: Implement prepare() method.
    }

    /**
     * @inheritDoc
     */
    public function with($entityClass)
    {
        // TODO: Implement with() method.
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        // TODO: Implement execute() method.
    }

    /**
     * @inheritDoc
     */
    public function getErrorInfo()
    {
        // TODO: Implement getErrorInfo() method.
    }

    /**
     * @inheritDoc
     */
    public function getResult()
    {
        // TODO: Implement getResult() method.
    }

    /**
     * @inheritDoc
     */
    public function getOneOrNullResult()
    {
        // TODO: Implement getOneOrNullResult() method.
    }

    /**
     * @inheritDoc
     */
    public function getArrayColumns()
    {
        // TODO: Implement getArrayColumns() method.
    }

    /**
     * @inheritDoc
     */
    public function getFirstResult()
    {
        // TODO: Implement getFirstResult() method.
    }

    /**
     * @inheritDoc
     */
    public function getSingleScalarResult()
    {
        // TODO: Implement getSingleScalarResult() method.
    }

    /**
     * @inheritDoc
     */
    public function getQueryLogs()
    {
        // TODO: Implement getQueryLogs() method.
    }

    /**
     * @inheritDoc
     */
    public function getObjectCollections(): array
    {
        // TODO: Implement getObjectCollections() method.
    }

    /**
     * @inheritDoc
     */
    public function fetchMode($fetchMode)
    {
        // TODO: Implement fetchMode() method.
    }
}