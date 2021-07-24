<?php

namespace Collective\Annotations\Events\Attributes\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Hears
{
    public array $events;

    public function __construct(array|string $events)
    {
        $this->events = is_array($events)? $events: [$events];
    }
}
