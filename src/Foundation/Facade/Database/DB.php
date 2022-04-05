<?php
namespace Laventure\Foundation\Facade\Database;


use Laventure\Component\Container\Facade\Facade;
use Laventure\Component\Database\Manager;


/**
 * @DB
 */
class DB extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Manager::class;
    }
}