<?php
namespace Laventure\Foundation\EventDispatcher;


use Closure;
use Laventure\Component\Container\Container;
use Laventure\Component\EventDispatcher\Common\AbstractEventDispatcher;
use Laventure\Component\EventDispatcher\Contract\EventDispatcherInterface;
use Laventure\Component\EventDispatcher\Event;
use Laventure\Component\EventDispatcher\EventListener;


/**
 * @EventDispatcher
*/
class EventDispatcher extends AbstractEventDispatcher
{


        /**
         * @var Container
        */
        protected $app;




        /**
         * @var mixed
        */
        protected $listeners = [];





        /**
          * EventDispatcher
          *
          * @param Container $app
        */
        public function __construct(Container $app)
        {
              $this->app = $app;
        }





        /**
         * @param string $eventName
         * @param EventListener|Closure|callable $listener
         * @return $this
        */
        public function addListener(string $eventName, $listener): AbstractEventDispatcher
        {
             if ($listener instanceof EventListener) {
                 $listener->setDispatcher($this);
             }

             $this->listeners[$eventName][] = $listener;

             return $this;
        }




        /**
          * @param Event $event
          * @param string|null $eventName
          * @param EventDispatcherInterface|null $dispatcher
         * @return void
        */
        public function dispatch(Event $event, string $eventName = null, EventDispatcherInterface $dispatcher = null)
        {
                 if ($eventName) {
                     $event->setName($eventName);
                 }

                 if ($listeners = $this->getListenersByEvent($event->getName())) {
                      $this->populateListeners($event, $listeners);
                 }

                 if ($dispatcher) {
                     $dispatcher->dispatch($event);
                 }
        }




        /**
         * @param Event $event
         * @param array $listeners
         * @return void
        */
        protected function populateListeners(Event $event, array $listeners)
        {
            foreach ($listeners as $listener) {
                if ($listener instanceof EventListener) {
                    $listener->handle($event);
                }

                if (is_callable($listener)) {
                    $this->app->call($listener, [$event]);
                }
            }
        }
}