<?php

namespace Collective\Annotations\Routing;

use Collective\Annotations\ScannerTrait;
use ReflectionClass;
use ReflectionException;

class Scanner
{
    use ScannerTrait;

    protected ScanStrategyInterface $strategy;

    /**
     * Create a new scanner instance.
     *
     * @param array $scan
     * @param ScanStrategyInterface $strategy
     */
    public function __construct(ScanStrategyInterface $strategy, array $scan = [])
    {
        $this->strategy = $strategy;
        $this->scan = $scan;
    }


    /**
     * Convert the scanned annotations/attributes into route definitions.
     *
     * @throws ReflectionException
     */
    public function getRouteDefinitions(): string
    {
        $output = '';

        foreach ($this->getEndpointsInClasses() as $endpoint) {
            $output .= $endpoint->toRouteDefinition().PHP_EOL.PHP_EOL;
        }

        return trim($output);
    }

    /**
     * Give information about the scanned annotations/attributes related to route definition.
     *
     * @throws ReflectionException
     */
    public function getRouteDefinitionsDetail(): array
    {
        $paths = array();
        foreach ($this->getEndpointsInClasses() as $endpoint) {
            foreach ($endpoint->toRouteDefinitionDetail() as $path) {
                $paths[] = $path;
            }
        }

        return $paths;
    }

    /**
     * @return ScanStrategyInterface
     */
    public function getStrategy(): ScanStrategyInterface
    {
        return $this->strategy;
    }

    /**
     * Scan the directory and generate the route manifest.
     *
     * @return EndpointCollection
     * @throws ReflectionException
     */
    protected function getEndpointsInClasses(): EndpointCollection
    {
        $endpoints = new EndpointCollection();

        foreach ($this->getClassesToScan() as $class) {
            $endpoints = $endpoints->merge($this->getEndpointsInClass($class));
        }

        return $endpoints;
    }

    /**
     * Build the Endpoints for the given class.
     *
     * @param ReflectionClass $class
     * @return EndpointCollection
     * @throws ReflectionException
     */
    protected function getEndpointsInClass(ReflectionClass $class): EndpointCollection
    {
        $endpoints = new EndpointCollection();

        foreach ($this->strategy->getMethodEndpoints($class) as $method => $methodEndpoint) {
            $this->addEndpoint($endpoints, $class, $method, $methodEndpoint);
        }

        foreach ($this->strategy->getClassEndpoints($class) as $classEndpoint) {
            $classEndpoint->modifyCollection($endpoints, $class);
        }

        return $endpoints;
    }

    /**
     * Create a new endpoint in the collection.
     *
     * @param EndpointCollection $endpoints
     * @param ReflectionClass $class
     * @param string $method
     * @param array $annotations
     *
     * @return void
     * @throws ReflectionException
     */
    protected function addEndpoint(
        EndpointCollection $endpoints,
        ReflectionClass    $class,
        string             $method,
        array              $annotations
    ) {
        $endpoints->push($endpoint = new MethodEndpoint([
            'reflection' => $class, 'method' => $method, 'uses' => $class->name.'@'.$method,
        ]));

        foreach ($annotations as $annotation) {
            $annotation->modify($endpoint, $class->getMethod($method));
        }
    }
}
