<?php
namespace Laventure\Component\Http\Middleware;


use Laventure\Component\Http\Message\RequestHandlerInterface;
use Laventure\Component\Http\Middleware\Contract\MiddlewareInterface;
use Laventure\Component\Http\Request\Request;
use Laventure\Component\Http\Response\Response;


/**
 * @Middleware
*/
class Middleware
{

    /**
     * @var Closure
    */
    protected $start;



    /**
     * @var array
    */
    protected $middlewares = [];



    /**
     * MiddlewareStack constructor.
    */
    public function __construct()
    {
        $this->start = function (Request $request) {
            return '';
        };
    }



    /**
     * @param mixed $middleware
     * @return $this
    */
    public function add($middleware): self
    {
        $next = $this->start;

        $this->start = function (Request $request) use ($middleware, $next) {
              return $this->invoke($middleware, $request, $next);
        };

        if (is_object($middleware)) {
            $this->middlewares[] = $middleware;
        }

        return $this;
    }




    /**
     * @param array $middlewares
     * @return $this
    */
    public function addMiddlewares(array $middlewares): self
    {
        foreach ($middlewares as $middleware) {
            $this->add($middleware);
        }

        return $this;
    }



    /**
     * Run all middlewares
     * @param Request $request
     * @return mixed
    */
    public function handle(Request $request)
    {
         return call_user_func($this->start, $request);
    }




    /**
     * @return array
    */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }




    /**
     * @param mixed $middleware
     * @param Request $request
     * @param callable $next
     * @return mixed
    */
    protected function invoke($middleware, Request $request, callable $next)
    {
        if (method_exists($middleware, '__invoke')) {
            $middleware->__invoke($request, $next);
            return $next($request);
        }

        if ($middleware instanceof RequestHandlerInterface) {
            $next = $middleware->handle($request, $next);
        }

        if ($middleware instanceof MiddlewareInterface) {
            $middleware->terminate($request, new Response());
        }

        return $next($request);
    }
}