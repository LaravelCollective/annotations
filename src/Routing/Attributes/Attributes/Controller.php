<?php

namespace Collective\Annotations\Routing\Attributes\Attributes;

use Attribute;
use Collective\Annotations\Routing\Annotations\Annotations\Controller as BaseController;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Controller extends BaseController
{
    public function __construct(
        ?string $prefix = null,
        ?string $domain = null,
    ) {
        parent::__construct(array_filter([
            'prefix' => $prefix,
            'domain' => $domain,
        ], fn ($item) => $item !== null));
    }
}
