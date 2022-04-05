<?php
namespace Laventure\Component\Http\Session\Utils;



/**
 * @SessionStarter
*/
class SessionStarter implements SessionStarterInterface
{

     /**
      * @return bool
     */
     public function start(): bool
     {
         if (session_status() === PHP_SESSION_NONE) {
             return session_start();
         }

         return false;
     }
}