<?php

namespace App\Http\Controllers;

use Collective\Annotations\Routing\Attributes\Attributes\Controller;
use Collective\Annotations\Routing\Attributes\Attributes\Get;

/**
 * @Controller(prefix="/any-prefix")
 */
#[Controller(prefix: '/any-prefix')]
class PrefixController
{
    /**
     * @Get("route-with-prefix", as="route.with.prefix")
    */
    #[Get(path: 'route-with-prefix', as: 'route.with.prefix')]
    public function withPrefixAnnotations()
    {
    }

    /**
     * @Get("route-without-prefix", as="route.without.prefix", no_prefix="true")
    */
    #[Get(path: 'route-without-prefix', as: 'route.without.prefix', noPrefix: true)]
    public function withoutPrefixAnnotations()
    {
    }
}
