<?php
namespace Laventure\Foundation\Provider;


use Laventure\Component\Container\ServiceProvider\ServiceProvider;
use Laventure\Component\Debug\Exception\ErrorHandler;
use Laventure\Component\Debug\Exception\ErrorHandlerInterface;



/**
 * @ErrorHandlerServiceProvider
*/
class ErrorHandlerServiceProvider extends ServiceProvider
{

    /**
     * @var array
    */
    protected $provides = [
         ErrorHandlerInterface::class => ['exception.handler', ErrorHandler::class]
    ];


    /**
     * @inheritDoc
    */
    public function register()
    {
         $this->app->singleton(ErrorHandler::class, function () {
               return new ErrorHandler();
         });
    }
}