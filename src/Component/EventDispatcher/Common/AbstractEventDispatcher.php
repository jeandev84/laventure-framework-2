<?php
namespace Laventure\Component\EventDispatcher\Common;


use Laventure\Component\EventDispatcher\Contract\EventDispatcherInterface;
use Laventure\Component\EventDispatcher\Event;
use Laventure\Component\EventDispatcher\EventListener;


/**
 * @AbstractEventDispatcher
*/
abstract class AbstractEventDispatcher implements EventDispatcherInterface
{

    /**
     * @var mixed
    */
    protected $listeners = [];




    /**
     * @return EventListener[]
    */
    public function getListeners(): array
    {
        return $this->listeners;
    }



    /**
     * Get listeners by event name
     * @param $eventName
     * @return EventListener[]
    */
    public function getListenersByEvent($eventName): array
    {
        if(! $this->hasListeners($eventName)) {
            return [];
        }

        return $this->listeners[$eventName];
    }



    /**
     * @param string $eventName
     * @return bool
    */
    public function hasListeners(string $eventName): bool
    {
        return isset($this->listeners[$eventName]);
    }



    /**
     * @param Event $event
     * @return void
    */
    abstract public function dispatch(Event $event);
}