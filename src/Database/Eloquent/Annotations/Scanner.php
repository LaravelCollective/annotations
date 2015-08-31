<?php namespace Collective\Annotations\Database\Eloquent\Annotations;

use ReflectionClass;
use Collective\Annotations\AnnotationScanner;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Symfony\Component\Finder\Finder;

class Scanner extends AnnotationScanner {

    /**
     * Create a new event scanner instance.
     *
     * @param  array $scan
     *
     * @return void
     */
    public function __construct(array $scan)
    {
        $this->scan = $scan;

        foreach (Finder::create()->files()->in(__DIR__ . '/Annotations') as $file)
        {
            AnnotationRegistry::registerFile($file->getRealPath());
        }
    }

    /**
     * Convert the scanned annotations into route definitions.
     *
     * @return string
     * @throws InvalidBindingResolverException
     */
    public function getModelDefinitions()
    {
        $output = '';

        $reader = $this->getReader();

        foreach ($this->getClassesToScan() as $class)
        {
            $annotations = $reader->getClassAnnotations($class);

            if ( count($annotations) > 0 && ! $this->extendsEloquent($class))
            {
                throw new InvalidBindingResolverException('Class [' . $class->name . '] is not a subclass of [Illuminate\Database\Eloquent\Model].');
            }

            foreach ($annotations as $annotation)
            {
                $output .= $this->buildBinding($annotation, $class->name);
            }
        }

        return trim($output);
    }

    /**
     * Determine if a class extends Eloquent
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
     * @param  Bind   $annotation
     * @param  string $class
     *
     * @return string
     */
    protected function buildBinding($annotation, $class)
    {
        if ($annotation->binder === null) {
            $method = 'model';
            $binder = $class;
        } else {
            $method = 'bind';
            $binder = $annotation->binder;
        }
        return sprintf('$router->%s(\'%s\', \'%s\');', $method, $annotation->binding, $binder) . PHP_EOL;
    }
}
