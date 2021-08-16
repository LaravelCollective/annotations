<?php

namespace Collective\Annotations\Routing;

use ReflectionClass;

interface ScanStrategyInterface
{
    public function getClassEndpoints(ReflectionClass $class): array;

    public function getMethodEndpoints(ReflectionClass $class): array;
}