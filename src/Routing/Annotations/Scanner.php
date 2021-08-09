<?php

namespace Collective\Annotations\Routing\Annotations;

use Collective\Annotations\AnnotationScanner;
use Collective\Annotations\Routing\RouteScannerInterface;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\SimpleAnnotationReader;
use ReflectionClass;
use Symfony\Component\Finder\Finder;

class Scanner extends AnnotationScanner implements RouteScannerInterface
{
    /**
     * Create a new scanner instance.
     *
     * @param array $scan
     *
     * @return void
     */
    public function __construct(array $scan)
    {
        parent::__construct($scan);

        foreach (Finder::create()->files()->in(__DIR__.'/Annotations') as $file) {
            AnnotationRegistry::registerFile($file->getRealPath());
        }
    }


    /**
     * @inheritDoc
     */
    public function getRouteDefinitions(): string
    {
        $output = '';

        foreach ($this->getEndpointsInClasses($this->getReader()) as $endpoint) {
            $output .= $endpoint->toRouteDefinition().PHP_EOL.PHP_EOL;
        }

        return trim($output);
    }

    /**
     * @inheritDoc
     */
    public function getRouteDefinitionsDetail(): array
    {
        $paths = array();
        foreach ($this->getEndpointsInClasses($this->getReader()) as $endpoint) {
            /* @var \Collective\Annotations\Routing\Annotations\MethodEndpoint $endpoint */
            foreach ($endpoint->toRouteDefinitionDetail() as $path) {
                $paths[] = $path;
            }
        }

        return $paths;
    }

    /**
     * Scan the directory and generate the route manifest.
     *
     * @param \Doctrine\Common\Annotations\SimpleAnnotationReader $reader
     *
     * @return \Collective\Annotations\Routing\Annotations\EndpointCollection
     */
    protected function getEndpointsInClasses(SimpleAnnotationReader $reader)
    {
        $endpoints = new EndpointCollection();

        foreach ($this->getClassesToScan() as $class) {
            $endpoints = $endpoints->merge($this->getEndpointsInClass(
                $class, new AnnotationSet($class, $reader)
            ));
        }

        return $endpoints;
    }

    /**
     * Build the Endpoints for the given class.
     *
     * @param \ReflectionClass                                          $class
     * @param \Collective\Annotations\Routing\Annotations\AnnotationSet $annotations
     *
     * @return \Collective\Annotations\Routing\Annotations\EndpointCollection
     */
    protected function getEndpointsInClass(ReflectionClass $class, AnnotationSet $annotations)
    {
        $endpoints = new EndpointCollection();

        foreach ($annotations->method as $method => $methodAnnotations) {
            $this->addEndpoint($endpoints, $class, $method, $methodAnnotations);
        }

        foreach ($annotations->class as $annotation) {
            $annotation->modifyCollection($endpoints, $class);
        }

        return $endpoints;
    }

    /**
     * Create a new endpoint in the collection.
     *
     * @param \Collective\Annotations\Routing\Annotations\EndpointCollection $endpoints
     * @param \ReflectionClass                                               $class
     * @param string                                                         $method
     * @param array                                                          $annotations
     *
     * @return void
     */
    protected function addEndpoint(EndpointCollection $endpoints, ReflectionClass $class,
                                   $method, array $annotations)
    {
        $endpoints->push($endpoint = new MethodEndpoint([
            'reflection' => $class, 'method' => $method, 'uses' => $class->name.'@'.$method,
        ]));

        foreach ($annotations as $annotation) {
            $annotation->modify($endpoint, $class->getMethod($method));
        }
    }
}
