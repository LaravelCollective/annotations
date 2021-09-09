<?php

namespace Collective\Annotations\Routing;

use ReflectionClass;

interface ScanStrategyInterface
{
    /**
     * @param ReflectionClass $class
     * @return Meta[]
     */
    public function getClassMetaList(ReflectionClass $class): array;

    /**
     * @param ReflectionClass $class
     * @return Meta[][]
     */
    public function getMethodMetaLists(ReflectionClass $class): array;
}