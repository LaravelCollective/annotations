<?php


namespace Collective\Annotations\Events;

use ReflectionMethod;

interface ScanStrategyInterface
{
    /**
     * Get information for events of method
     *
     * @param ReflectionMethod $method
     * @return string[][]
     */
    public function getEvents(ReflectionMethod $method): array;
}
