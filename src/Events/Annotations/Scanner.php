<?php

namespace Collective\Annotations\Events\Annotations;

use Collective\Annotations\AnnotationScanner;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Symfony\Component\Finder\Finder;

class Scanner extends AnnotationScanner
{
    /**
     * Create a new event scanner instance.
     *
     * @param array $scan
     *
     * @return void
     */
    public function __construct(array $scan)
    {
        $this->scan = $scan;

        foreach (Finder::create()->files()->in(__DIR__.'/Annotations') as $file) {
            AnnotationRegistry::registerFile($file->getRealPath());
        }
    }

    /**
     * Convert the scanned annotations into route definitions.
     *
     * @return string
     */
    public function getEventDefinitions()
    {
        $output = '';

        $reader = $this->getReader();

        foreach ($this->getClassesToScan() as $class) {
            foreach ($class->getMethods() as $method) {
                foreach ($reader->getMethodAnnotations($method) as $annotation) {
                    $output .= $this->buildListener($class->name, $method->name, $annotation->events);
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
