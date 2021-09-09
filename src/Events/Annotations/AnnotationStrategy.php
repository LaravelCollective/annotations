<?php

namespace Collective\Annotations\Events\Annotations;

use Collective\Annotations\AnnotationStrategyTrait;
use ReflectionMethod;

class AnnotationStrategy implements ScanStrategyInterface
{
    use AnnotationStrategyTrait;

    /**
     * @inheritDoc
     */
    public function getEvents(ReflectionMethod $method): array
    {
        return array_map(
            function ($annotation) {
                return $annotation->events;
            },
            $this->getReader()->getMethodAnnotations($method)
        );
    }
}
