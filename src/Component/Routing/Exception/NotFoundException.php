<?php
namespace Laventure\Component\Routing\Exception;


use Throwable;


/**
 * @NotFoundException
*/
class NotFoundException extends BadRequestException
{
       public function __construct($message = "", $code = 404, Throwable $previous = null)
       {
           parent::__construct($message, $code, $previous);
       }
}