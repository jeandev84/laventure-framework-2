<?php
namespace Laventure\Foundation\Facade\Database;

use Laventure\Component\Container\Facade\Facade;


/**
 * @Schema
*/
class Schema extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return '@schema';
    }
}