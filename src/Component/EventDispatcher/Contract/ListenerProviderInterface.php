<?php
namespace Laventure\Component\EventDispatcher\Contract;

use Laventure\Component\EventDispatcher\Event;



/**
 * @ListenerProviderInterface
*/
interface ListenerProviderInterface
{
    /**
     * @param Event $event
     *   An event for which to return the relevant listeners.
     * @return iterable<callable>
     *   An iterable (array, iterator, or generator) of callables.  Each
     *   callable MUST be type-compatible with $event.
    */
    public function getListenersForEvent(Event $event) : iterable;
}