<?php
namespace Laventure\Foundation\Facade\Console;


use Laventure\Component\Console\Console;
use Laventure\Component\Container\Facade\Facade;


/**
 * @Schedule
*/
class Schedule extends Facade
{

    /**
     * @return string
    */
    protected static function getFacadeAccessor(): string
    {
        return Console::class;
    }
}