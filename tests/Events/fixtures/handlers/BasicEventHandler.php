<?php

namespace App\Handlers\Events;

use App\Events\BasicEvent;
use Collective\Annotations\Events\Attributes\Attributes\Hears;

class BasicEventHandler
{
    /**
     * @Hears("BasicEventFired")
     */
    #[Hears(['BasicEventFired'])]
    public function handle(BasicEvent $event)
    {
        // do things
    }
}

