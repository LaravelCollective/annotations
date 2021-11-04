<?php

namespace Collective\Annotations\Routing\Attributes;

use Collective\Annotations\Routing\ScanStrategyInterface;
use ReflectionAttribute;
use ReflectionClass;

class AttributeStrategy implements ScanStrategyInterface
{
    /**
     * @inheritDoc
     */
    public function getClassMetaList(ReflectionClass $class): array
    {
        return array_map(
            fn (ReflectionAttribute $attribute) => $attribute->newInstance(),
            $class->getAttributes()
        );
    }

    /**
     * @inheritDoc
     */
    public function getMethodMetaLists(ReflectionClass $class): array
    {
        $attributes = [];

        foreach ($class->getMethods() as $method) {
            if ($method->class == $class->name) {
                $results = $method->getAttributes();

                if (count($results) > 0) {
                    $attributes[$method->name] = array_map(
                        fn (ReflectionAttribute $attribute) => $attribute->newInstance(),
                        $results
                    );
                }
            }
        }

        return $attributes;
    }
}
