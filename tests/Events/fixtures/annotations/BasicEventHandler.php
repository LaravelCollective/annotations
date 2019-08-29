<?php

namespace App\Handlers\Events;

use App\Events\BasicEvent;

class BasicEventHandler
{
    /**
     * @Hears("BasicEventFired")
     */
    public function handle(BasicEvent $event)
    {
        // do things
    }
}

