<?php

namespace Collective\Annotations\Routing\Attributes\Attributes;

use Attribute;
use Collective\Annotations\Routing\Annotations\Annotations\Where as BaseWhere;
use Collective\Annotations\Routing\Annotations\EndpointCollection;
use ReflectionClass;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Where extends BaseWhere
{
    public function __construct(array $values)
    {
        parent::__construct($values);
    }

    public function modifyCollection(EndpointCollection $endpoints, ReflectionClass $class)
    {
        foreach ($endpoints->getAllPaths() as $path) {
            $path->where = array_merge($path->where, $this->values);
        }
    }
}
