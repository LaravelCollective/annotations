<?php

namespace Collective\Annotations\Routing\Annotations\Annotations;

use Collective\Annotations\Routing\EndpointCollection;
use Collective\Annotations\Routing\MethodEndpoint;
use Collective\Annotations\Routing\ResourceEndpoint;
use Collective\Annotations\Routing\Meta;
use Illuminate\Support\Collection;
use ReflectionClass;

/**
 * @Annotation
 */
class Resource extends Meta
{
    /**
     * All of the resource controller methods.
     *
     * @var array
     */
    protected $methods = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'];

    /**
     * {@inheritdoc}
     */
    public function modifyCollection(EndpointCollection $endpoints, ReflectionClass $class)
    {
        $endpoints->push(new ResourceEndpoint([
            'reflection' => $class, 'name' => $this->value, 'names' => (array) $this->names,
            'only'       => (array) $this->only, 'except' => (array) $this->except,
            'middleware' => $this->getMiddleware($endpoints),
        ]));
    }

    /**
     * Get all of the middleware defined on the resource method endpoints.
     *
     * @param EndpointCollection $endpoints
     *
     * @return array
     */
    protected function getMiddleware(EndpointCollection $endpoints): array
    {
        return $this->extractFromEndpoints($endpoints, 'middleware');
    }

    /**
     * Extract method items from endpoints for the given key.
     *
     * @param EndpointCollection $endpoints
     * @param string $key
     *
     * @return array
     */
    protected function extractFromEndpoints(EndpointCollection $endpoints, string $key): array
    {
        $items = [
            'index' => [], 'create' => [], 'store' => [], 'show' => [],
            'edit'  => [], 'update' => [], 'destroy' => [],
        ];

        foreach ($this->getEndpointsWithResourceMethods($endpoints) as $endpoint) {
            $items[$endpoint->method] = array_merge($items[$endpoint->method], $endpoint->{$key});
        }

        return $items;
    }

    /**
     * Get all of the resource method endpoints with pathless filters.
     *
     * @param EndpointCollection $endpoints
     *
     * @return array
     */
    protected function getEndpointsWithResourceMethods(EndpointCollection $endpoints): array
    {
        return Collection::make($endpoints)->filter(function ($endpoint) {
            return $endpoint instanceof MethodEndpoint &&
                    in_array($endpoint->method, $this->methods);
        })->all();
    }
}
