<?php

namespace App\Handlers\Events;

use App\Events\AnotherEvent;
use App\Events\BasicEvent;
use Collective\Annotations\Events\Attributes\Attributes\Hears;

class MultipleEventHandler
{
    /**
     * @Hears("BasicEventFired")
     */
    #[Hears('BasicEventFired')]
    public function handleBasicEvent(BasicEvent $event)
    {
        // do things
    }

    /**
     * @Hears("BasicEventFired")
     */
    #[Hears('BasicEventFired')]
    public function handleBasicEventAgain(BasicEvent $event)
    {
        // do things
    }

    /**
     * @Hears("AnotherEventFired")
     */
    #[Hears('AnotherEventFired')]
    public function handleAnotherEvent(AnotherEvent $event)
    {
        // do things
    }
}

