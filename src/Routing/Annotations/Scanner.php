<?php

namespace Collective\Annotations\Routing\Annotations;

use Collective\Annotations\Routing\Annotations\Annotations\Annotation;
use Collective\Annotations\Scanner as BaseScanner;
use ReflectionClass;
use ReflectionException;

class Scanner extends BaseScanner
{
    /**
     * @var ScanStrategyInterface
     */
    protected $strategy;

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
            $output .= $endpoint->toRouteDefinition() . PHP_EOL . PHP_EOL;
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

        foreach ($this->strategy->getMethodMetaLists($class) as $method => $methodMetaList) {
            $this->addEndpoint($endpoints, $class, $method, $methodMetaList);
        }

        foreach ($this->strategy->getClassMetaList($class) as $classMeta) {
            $classMeta->modifyCollection($endpoints, $class);
        }

        return $endpoints;
    }

    /**
     * Create a new endpoint in the collection.
     *
     * @param EndpointCollection $endpoints
     * @param ReflectionClass $class
     * @param string $method
     * @param Annotation[] $metaList
     *
     * @return void
     * @throws ReflectionException
     */
    protected function addEndpoint(
        EndpointCollection $endpoints,
        ReflectionClass    $class,
        string             $method,
        array              $metaList
    ) {
        $endpoints->push($endpoint = new MethodEndpoint([
            'reflection' => $class, 'method' => $method, 'uses' => $class->name . '@' . $method,
        ]));

        foreach ($metaList as $meta) {
            $meta->modify($endpoint, $class->getMethod($method));
        }
    }
}
