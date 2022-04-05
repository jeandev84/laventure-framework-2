<?php
namespace Laventure\Foundation\Providers;


use Laventure\Component\Container\ServiceProvider\ServiceProvider;
use Laventure\Component\EventDispatcher\Contract\EventDispatcherInterface;
use Laventure\Foundation\EventDispatcher\EventDispatcher;


/**
 * @EventDispatcherServiceProvider
*/
class EventDispatcherServiceProvider extends ServiceProvider
{

    /**
     * @var array
    */
    protected $provides = [
       'events' => [EventDispatcher::class, EventDispatcherInterface::class]
    ];



    /**
     * @inheritDoc
    */
    public function register()
    {
         $this->app->singleton('events', function () {
              return new EventDispatcher($this->app);
         });
    }
}