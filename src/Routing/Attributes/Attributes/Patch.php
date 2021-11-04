<?php

namespace Collective\Annotations\Routing\Attributes\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Patch extends Route
{
    //
}
