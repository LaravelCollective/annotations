<?php

namespace Collective\Annotations\Routing\Attributes\Attributes;

use Collective\Annotations\Routing\Annotations\Annotations\Route as BaseRoute;

abstract class Route extends BaseRoute
{
    public function __construct(
        string $path,
        ?string $as = null,
        ?string $domain = null,
        ?array $middleware = null,
        ?array $where = null,
        ?bool $noPrefix = null,
        ?string $after = null
    ) {
        parent::__construct(array_filter([
            'value' => $path,
            'as' => $as,
            'middleware' => $middleware,
            'domain' => $domain,
            'where' => $where,
            'no_prefix' => $noPrefix,
            'after' => $after,
        ], fn ($item) => $item !== null));
    }
}
