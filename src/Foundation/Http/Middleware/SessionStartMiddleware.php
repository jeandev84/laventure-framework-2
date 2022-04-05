<?php
namespace Laventure\Foundation\Http\Middleware;


use Laventure\Component\Http\Request\Request;
use Laventure\Component\Http\Session\Utils\SessionStarter;


/**
 * @SessionStartMiddleware
*/
class SessionStartMiddleware
{

       /**
        * @var SessionStarter
       */
       protected $session;



       /**
        * @param SessionStarter $session
       */
       public function __construct(SessionStarter $session)
       {
             $this->session = $session;
       }



       /**
        * @param Request $request
        * @param callable $next
        * @return mixed
       */
       public function __invoke(Request $request, callable $next)
       {
             $this->session->start();

             return $next($request);
       }
}