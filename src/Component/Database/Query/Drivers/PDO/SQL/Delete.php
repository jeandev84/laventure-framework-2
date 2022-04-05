<?php
namespace Laventure\Component\Database\Query\Drivers\PDO\SQL;


use Laventure\Component\Database\Query\Builders\DeleteBuilder;
use Laventure\Component\Database\Query\Drivers\PDO\Common\PdoConditionBuilderTrait;


/**
 * @Delete
*/
class Delete extends DeleteBuilder
{
    use PdoConditionBuilderTrait;
}