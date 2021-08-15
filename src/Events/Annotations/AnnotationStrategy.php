<?php

namespace Collective\Annotations\Events\Annotations;

use Collective\Annotations\AnnotationStrategyTrait;
use Collective\Annotations\Events\ScanStrategyInterface;
use ReflectionMethod;

class AnnotationStrategy implements ScanStrategyInterface
{
    use AnnotationStrategyTrait;

    public function getEvents(ReflectionMethod $method): array
    {
        return array_map(
            fn ($annotation) => $annotation->events,
            $this->getReader()->getMethodAnnotations($method)
        );
    }
}
