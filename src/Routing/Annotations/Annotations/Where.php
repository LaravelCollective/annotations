<?php

namespace Collective\Annotations\Routing\Annotations\Annotations;

use Collective\Annotations\Routing\EndpointCollection;
use Collective\Annotations\Routing\MethodEndpoint;
use Collective\Annotations\Routing\Endpoint;
use ReflectionClass;
use ReflectionMethod;

/**
 * @Annotation
 */
class Where extends Endpoint
{
    /**
     * {@inheritdoc}
     */
    public function modify(MethodEndpoint $endpoint, ReflectionMethod $method)
    {
        foreach ($endpoint->getPaths() as $path) {
            $path->where = array_merge($path->where, (array) $this->values);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function modifyCollection(EndpointCollection $endpoints, ReflectionClass $class)
    {
        foreach ($endpoints->getAllPaths() as $path) {
            $path->where = array_merge($path->where, $this->value);
        }
    }
}
