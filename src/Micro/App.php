<?php
namespace Laventure\Micro;

use Closure;
use Exception;
use Laventure\Component\Http\Message\RequestInterface;
use Laventure\Component\Http\Request\Request;
use Laventure\Component\Http\Response\Response;
use Laventure\Component\Routing\Collection\Route;
use Laventure\Component\Routing\Router;
use Laventure\Micro\Exception\BadRequestException;
use Laventure\Micro\Exception\ResponseException;


/**
 * @App
*/
class App
{

      /**
       * @var DI
      */
      public static $app;




      /**
       * @var App
      */
      protected static $instance;





      /**
       * @var Router
      */
      protected $router;





      /**
       * @param array $definition
      */
      private function __construct(array $definition)
      {
             self::$app    = new DI($definition);
             $this->router = new Router();
      }





      /**
       * @param array $definition
       * @return App|static
      */
      public static function express(array $definition): App
      {
           if (! self::$instance) {
               self::$instance = new static($definition);
           }

           return self::$instance;
      }



      /**
       * @param $methods
       * @param $path
       * @param Closure $closure
       * @return Route
       * @throws Exception
      */
      public function map($methods, $path, Closure $closure): Route
      {
          return $this->router->map($methods, $path, $closure);
      }





      /**
       * @param $path
       * @param Closure $closure
       * @return Route
       * @throws Exception
      */
      public function get($path, Closure $closure): Route
      {
          return $this->router->get($path, $closure);
      }




      /**
       * @param $path
       * @param Closure $closure
       * @return Route
       * @throws Exception
      */
      public function post($path, Closure $closure): Route
      {
           return $this->router->post($path, $closure);
      }




      /**
       * Run Micro application
       *
       * @param RequestInterface|null $request
       * @return void
       * @throws BadRequestException
       * @throws ResponseException
     */
      public function run(RequestInterface $request = null)
      {
           if (! $request) {
               $request = Request::createFromGlobals();
           }

           if(! $route = $this->router->match($request->getMethod(), $path = $request->getRequestUri())) {
                throw new BadRequestException("Route {$path} not found.", 404);
           }

           $closure  = $route->getCallback();
           $params   = $route->getMatches();
           $request->setAttributes($params);

           $response = $closure($request, new Response(), $params);

           if (! $response instanceof Response) {
                throw new ResponseException("Response handle must be an instance of Response.");
           }

           $response->send();
           $response->sendBody();
      }
}