<?php

namespace Collective\Annotations\Events\Attributes;

use Collective\Annotations\BaseScanner;
use ReflectionAttribute;
use ReflectionMethod;

class Scanner extends BaseScanner
{
    /**
     * Convert the scanned attributes into event definitions.
     *
     * @return string
     */
    public function getEventDefinitions()
    {
        $output = '';

        foreach ($this->getClassesToScan() as $class) {
            /** @var ReflectionMethod $method */
            foreach ($class->getMethods() as $method) {

                $attributes = array_map(
                    fn (ReflectionAttribute $attribute) => $attribute->newInstance(),
                    $method->getAttributes()
                );

                foreach ($attributes as $attribute) {
                    $output .= $this->buildListener($class->name, $method->name, $attribute->events);
                }
            }
        }

        return trim($output);
    }

    /**
     * Build the event listener for the class and method.
     *
     * @param string $class
     * @param string $method
     * @param array  $events
     *
     * @return string
     */
    protected function buildListener($class, $method, $events)
    {
        return sprintf('$events->listen(%s, \''.$class.'@'.$method.'\');', var_export($events, true)).PHP_EOL;
    }
}
