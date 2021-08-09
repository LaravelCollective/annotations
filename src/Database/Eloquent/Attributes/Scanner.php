<?php

namespace Collective\Annotations\Database\Eloquent\Attributes;

use Collective\Annotations\BaseScanner;
use Collective\Annotations\Database\Eloquent\Annotations\InvalidBindingResolverException;
use ReflectionClass;

class Scanner extends BaseScanner
{
    /**
     * Convert the scanned annotations into route definitions.
     *
     * @throws InvalidBindingResolverException
     *
     * @return string
     */
    public function getModelDefinitions()
    {
        $output = '';

        /** @var ReflectionClass $class */
        foreach ($this->getClassesToScan() as $class) {
            $attributes = $class->getAttributes();

            if (count($attributes) > 0 && !$this->extendsEloquent($class)) {
                throw new InvalidBindingResolverException('Class ['.$class->name.'] is not a subclass of [Illuminate\Database\Eloquent\Model].');
            }

            foreach ($attributes as $attribute) {
                $output .= $this->buildBinding($attribute->newInstance()->binding, $class->name);
            }
        }

        return trim($output);
    }

    /**
     * Determine if a class extends Eloquent.
     *
     * @param ReflectionClass $class
     *
     * @return bool
     */
    protected function extendsEloquent(ReflectionClass $class)
    {
        return $class->isSubclassOf('Illuminate\Database\Eloquent\Model');
    }

    /**
     * Build the event listener for the class and method.
     *
     * @param string $binding
     * @param string $class
     *
     * @return string
     */
    protected function buildBinding($binding, $class)
    {
        return sprintf('$router->model(\'%s\', \'%s\');', $binding, $class).PHP_EOL;
    }
}
