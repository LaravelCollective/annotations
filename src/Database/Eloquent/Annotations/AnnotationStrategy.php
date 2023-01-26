<?php

namespace Collective\Annotations\Database\Eloquent\Annotations;

use Collective\Annotations\AnnotationStrategyTrait;
use Collective\Annotations\Database\Eloquent\Annotations\Annotations\Bind;
use Collective\Annotations\Database\ScanStrategyInterface;
use ReflectionClass;

class AnnotationStrategy implements ScanStrategyInterface
{
    use AnnotationStrategyTrait;

    /**
     * @inheritDoc
     */
    public function support(ReflectionClass $class): bool
    {
        return count($this->getReader()->getClassAnnotations($class)) > 0;
    }

    /**
     * @inheritDoc
     */
    public function getBindings(ReflectionClass $class): array
    {
        return $this->getReader()->getClassAnnotations($class);
    }

}
