<?php
namespace Laventure\Component\Database\Query\Drivers\PDO\SQL;


use Laventure\Component\Database\Query\Builders\SelectBuilder;
use Laventure\Component\Database\Query\Drivers\PDO\Common\PdoConditionBuilderTrait;


/**
 * @Select
*/
class Select extends SelectBuilder
{
    use PdoConditionBuilderTrait;
}