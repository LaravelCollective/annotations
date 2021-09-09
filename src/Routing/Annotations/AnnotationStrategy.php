<?php

namespace Collective\Annotations\Routing\Annotations;

use Collective\Annotations\AnnotationStrategyTrait;
use ReflectionClass;

class AnnotationStrategy implements ScanStrategyInterface
{
    use AnnotationStrategyTrait;

    /**
     * @inheritDoc
     */
    public function getClassMetaList(ReflectionClass $class): array
    {
        return $this->getReader()->getClassAnnotations($class);
    }

    /**
     * @inheritDoc
     */
    public function getMethodMetaLists(ReflectionClass $class): array
    {
        $annotations = [];

        foreach ($class->getMethods() as $method) {
            if ($method->class == $class->name) {
                $results = $this->getReader()->getMethodAnnotations($method);

                if (count($results) > 0) {
                    $annotations[$method->name] = $results;
                }
            }
        }

        return $annotations;
    }
}
