<?php

namespace Collective\Annotations\Events;

interface EventScannerInterface
{
    /**
     * Convert the scanned annotations/attributes into event definitions.
     */
    public function getEventDefinitions(): string;
}