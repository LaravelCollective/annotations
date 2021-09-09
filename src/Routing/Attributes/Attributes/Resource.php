<?php

namespace Collective\Annotations\Routing\Attributes\Attributes;

use Attribute;
use Collective\Annotations\Routing\Annotations\Annotations\Resource as BaseResource;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Resource extends BaseResource
{
    public function __construct(
        string $name,
        ?array $only = null,
        ?array $except = null,
        ?array $names = null,
    ) {
        parent::__construct(array_filter([
            'value' => $name,
            'only' => $only,
            'except' => $except,
            'names' => $names,
        ], fn ($item) => $item !== null));
    }
}
