<?php

namespace Collective\Annotations\Routing\Annotations;

use Collective\Annotations\Routing\Annotations\Annotations\Annotation;
use ReflectionClass;

interface ScanStrategyInterface
{
    /**
     * @param ReflectionClass $class
     * @return Annotation[]
     */
    public function getClassMetaList(ReflectionClass $class): array;

    /**
     * @param ReflectionClass $class
     * @return Annotation[][]
     */
    public function getMethodMetaLists(ReflectionClass $class): array;
}