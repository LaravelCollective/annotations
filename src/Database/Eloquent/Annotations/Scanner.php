<?php

namespace Collective\Annotations\Database\Eloquent\Annotations;

use Collective\Annotations\AnnotationScanner;
use Doctrine\Common\Annotations\AnnotationRegistry;
use ReflectionClass;
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
     * @throws InvalidBindingResolverException
     *
     * @return string
     */
    public function getModelDefinitions()
    {
        $output = '';

        $reader = $this->getReader();

        foreach ($this->getClassesToScan() as $class) {
            $annotations = $reader->getClassAnnotations($class);

            if (count($annotations) > 0 && !$this->extendsEloquent($class)) {
                throw new InvalidBindingResolverException('Class ['.$class->name.'] is not a subclass of [Illuminate\Database\Eloquent\Model].');
            }

            foreach ($annotations as $annotation) {
                $output .= $this->buildBinding($annotation->binding, $class->name);
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
