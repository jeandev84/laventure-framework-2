<?php
namespace Laventure\Component\EventDispatcher;


use Laventure\Component\EventDispatcher\Common\AbstractEventDispatcher;

/**
 * @EventDispatcher
*/
class EventDispatcher extends AbstractEventDispatcher
{


    /**
     * @var EventListener[]
    */
    protected $listeners = [];




    /**
     * @param string $eventName
     * @param EventListener $listener
     * @return $this
    */
    public function addListener(string $eventName, EventListener $listener): EventDispatcher
    {
        $listener->setDispatcher($this);

        $this->listeners[$eventName][] = $listener;

        return $this;
    }





    /**
     * @inheritDoc
    */
    public function dispatch(Event $event)
    {
         foreach ($this->getListenersByEvent($event->getName()) as $listener) {
             $listener->handle($event);
         }
    }
}