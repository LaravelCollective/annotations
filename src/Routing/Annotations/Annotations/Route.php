<?php

namespace Collective\Annotations\Routing\Annotations\Annotations;

use Collective\Annotations\Routing\MethodEndpoint;
use Collective\Annotations\Routing\Path;
use Collective\Annotations\Routing\Endpoint;
use ReflectionMethod;

abstract class Route extends Endpoint
{
    /**
     * {@inheritdoc}
     */
    public function modify(MethodEndpoint $endpoint, ReflectionMethod $method)
    {
        $endpoint->addPath(new Path(
            strtolower(class_basename(get_class($this))), $this->domain, $this->value,
            $this->as, (array) $this->middleware, (array) $this->where, $this->no_prefix
        ));
    }
}
