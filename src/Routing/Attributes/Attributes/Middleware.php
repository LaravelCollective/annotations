<?php

namespace Collective\Annotations\Routing\Attributes\Attributes;

use Attribute;
use Collective\Annotations\Routing\Annotations\Annotations\Middleware as BaseMiddleware;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Middleware extends BaseMiddleware
{
    public function __construct(string $name, array $options = [])
    {
        $options['value'] = $name;
        parent::__construct($options);
    }
}
