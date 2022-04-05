<?php
namespace Laventure\Component\Routing\Group;



use Laventure\Component\Routing\Router;

/**
 * @RouteGroupInterface
*/
interface RouteGroupInterface
{


      /**
       * @return mixed
      */
      public function getAttributes();



      /**
       * @param callable $callback
       * @return mixed
      */
      public function callback(callable $callback);



      /**
       * @param Router $router
       * @return mixed
      */
      public function callRoutes(Router $router);
}