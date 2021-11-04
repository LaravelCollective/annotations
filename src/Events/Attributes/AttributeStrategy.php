<?php

namespace Collective\Annotations\Events\Attributes;

use Collective\Annotations\Events\ScanStrategyInterface;
use ReflectionAttribute;
use ReflectionMethod;

class AttributeStrategy implements ScanStrategyInterface
{
    /**
     * @inheritDoc
     */
    public function getEvents(ReflectionMethod $method): array
    {
        return array_map(
            fn (ReflectionAttribute $attribute) => $attribute->newInstance()->events,
            $method->getAttributes()
        );
    }
}
