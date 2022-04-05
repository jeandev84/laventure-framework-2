<?php
namespace Laventure\Component\Container\Exception;



use Throwable;

/**
 * @ContainerException
*/
class ContainerException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}