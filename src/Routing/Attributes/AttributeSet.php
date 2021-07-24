<?php

namespace Collective\Annotations\Routing\Attributes;

use Collective\Annotations\Routing\Annotations\Annotations\Annotation;
use ReflectionAttribute;
use ReflectionClass;

class AttributeSet
{
    /**
     * The class Attributes.
     *
     * @var Annotation[]
     */
    public array $class;

    /**
     * The method Attributes.
     *
     * @var Annotation[][]
     */
    public array $method;

    /**
     * Create a new annotation set instance.
     *
     * @param ReflectionClass $class
     *
     * @return void
     */
    public function __construct(ReflectionClass $class)
    {
        $this->class = array_map(
            fn (ReflectionAttribute $attribute) => $attribute->newInstance(),
            $class->getAttributes()
        );
        $this->method = $this->getMethodAttributes($class);
    }

    /**
     * Get the method attributes for a given class.
     *
     * @param ReflectionClass $class
     *
     * @return Annotation[]
     */
    protected function getMethodAttributes(ReflectionClass $class)
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
