<?php

namespace App\Handlers\Events;

use App\Events\AnotherEvent;
use App\Events\BasicEvent;

class MultipleEventHandler
{
    /**
     * @Hears("BasicEventFired")
     */
    public function handleBasicEvent(BasicEvent $event)
    {
        // do things
    }

    /**
     * @Hears("BasicEventFired")
     */
    public function handleBasicEventAgain(BasicEvent $event)
    {
        // do things
    }

    /**
     * @Hears("AnotherEventFired")
     */
    public function handleAnotherEvent(AnotherEvent $event)
    {
        // do things
    }
}

