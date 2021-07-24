<?php

namespace App\Handlers\Events\Attributes;

use App\Events\BasicEvent;
use Collective\Annotations\Events\Attributes\Attributes\Hears;

class BasicEventHandler
{
    #[Hears(['BasicEventFired'])]
    public function handle(BasicEvent $event)
    {
        // do things
    }
}

