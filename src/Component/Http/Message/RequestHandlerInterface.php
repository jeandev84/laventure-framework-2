<?php
namespace Laventure\Component\Http\Message;


use Laventure\Component\Http\Request\Request;


/**
 * @RequestHandlerInterface
*/
interface RequestHandlerInterface
{
     /**
      * @param Request $request
      * @param callable $next
      * @return mixed
     */
     public function handle(Request $request, callable $next);
}