<?php

namespace Collective\Annotations\Database;

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
     * @return BindInterface
     */
    public function getBindings(ReflectionClass $class): array;
}
