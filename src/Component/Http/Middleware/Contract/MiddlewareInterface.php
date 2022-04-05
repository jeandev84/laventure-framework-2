<?php
namespace Laventure\Component\Http\Middleware\Contract;


use Laventure\Component\Http\Message\RequestHandlerInterface;
use Laventure\Component\Http\Request\Request;
use Laventure\Component\Http\Response\Response;



/**
 * @MiddlewareInterface
*/
interface MiddlewareInterface extends RequestHandlerInterface
{
     public function terminate(Request $request, Response $response);
}