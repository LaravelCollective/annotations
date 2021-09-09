<?php

namespace Collective\Annotations\Database\Eloquent\Annotations;

use ReflectionClass;

interface ScanStrategyInterface
{
    /**
     * Check if the class is supported
     *
     * @param ReflectionClass $class
     * @return bool
     */
    public function support(ReflectionClass $class): bool;

    /**
     * Get information for Bindings of class
     *
     * @param ReflectionClass $class
     * @return string[]
     */
    public function getBindings(ReflectionClass $class): array;
}