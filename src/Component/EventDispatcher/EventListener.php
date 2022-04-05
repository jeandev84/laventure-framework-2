<?php
namespace Laventure\Component\EventDispatcher;

use Laventure\Component\EventDispatcher\Common\AbstractEventDispatcher;
use Laventure\Component\EventDispatcher\Contract\EventListenerInterface;


/**
 * @EventListener
*/
abstract class EventListener implements EventListenerInterface
{

    /**
     * @var AbstractEventDispatcher
    */
    protected $dispatcher;



    /**
     * @param AbstractEventDispatcher $dispatcher
     * @return void
    */
    public function setDispatcher(AbstractEventDispatcher $dispatcher)
    {
          $this->dispatcher = $dispatcher;
    }



    /**
     * @param Event $event
     * @return iterable
    */
    public function getListenersForEvent(Event $event): iterable
    {
         return $this->dispatcher->getListenersByEvent($event);
    }




    /** @param Event $event */
    abstract public function handle(Event $event);
}