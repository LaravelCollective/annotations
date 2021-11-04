<?php

namespace Collective\Annotations\Database\Eloquent\Attributes\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Bind
{
    public function __construct(public string $binding)
    {}
}
