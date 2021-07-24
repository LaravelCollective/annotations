<?php

namespace Collective\Annotations\Routing\Attributes;

use Collective\Annotations\Routing\Annotations\EndpointCollection;
use Collective\Annotations\Routing\Annotations\MethodEndpoint;
use Collective\Annotations\BaseScanner;
use ReflectionClass;

class Scanner extends BaseScanner
{
    /**
     * Convert the scanned annotations into route definitions.
     *
     * @return string
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
     * Give information about the scanned annotations related to route definition.
     *
     * @return array
     */
    public function getRouteDefinitionsDetail(): array
    {
        $paths = array();
        foreach ($this->getEndpointsInClasses() as $endpoint) {
            /* @var MethodEndpoint $endpoint */
            foreach ($endpoint->toRouteDefinitionDetail() as $path) {
                $paths[] = $path;
            }
        }

        return $paths;
    }

    /**
     * Scan the directory and generate the route manifest.
     *
     * @return EndpointCollection
     */
    protected function getEndpointsInClasses(): EndpointCollection
    {
        $endpoints = new EndpointCollection();

        foreach ($this->getClassesToScan() as $class) {
            $endpoints = $endpoints->merge($this->getEndpointsInClass(
                $class, new AttributeSet($class)
            ));
        }

        return $endpoints;
    }

    /**
     * Build the Endpoints for the given class.
     *
     * @param ReflectionClass $class
     * @param AttributeSet    $attributes
     *
     * @return EndpointCollection
     */
    protected function getEndpointsInClass(ReflectionClass $class, AttributeSet $attributes): EndpointCollection
    {
        $endpoints = new EndpointCollection();

        foreach ($attributes->method as $method => $methodAttributes) {
            $this->addEndpoint($endpoints, $class, $method, $methodAttributes);
        }

        foreach ($attributes->class as $attributes) {
            $attributes->modifyCollection($endpoints, $class);
        }

        return $endpoints;
    }

    /**
     * Create a new endpoint in the collection.
     *
     * @param EndpointCollection $endpoints
     * @param ReflectionClass    $class
     * @param string             $method
     * @param array              $attributes
     *
     * @return void
     */
    protected function addEndpoint(EndpointCollection $endpoints, ReflectionClass $class, $method, array $attributes)
    {
        $endpoints->push($endpoint = new MethodEndpoint([
            'reflection' => $class, 'method' => $method, 'uses' => $class->name.'@'.$method,
        ]));

        foreach ($attributes as $attribute) {
            $attribute->modify($endpoint, $class->getMethod($method));
        }
    }
}
