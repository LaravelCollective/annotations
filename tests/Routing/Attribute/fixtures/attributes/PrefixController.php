<?php

namespace App\Http\Controllers\Attributes;

use Collective\Annotations\Routing\Attributes\Attributes\Controller;
use Collective\Annotations\Routing\Attributes\Attributes\Get;

#[Controller(prefix: '/any-prefix')]
class PrefixController
{
    #[Get(path: 'route-with-prefix', as: 'route.with.prefix')]
    public function withPrefixAnnotations()
    {
    }

    #[Get(path: 'route-without-prefix', as: 'route.without.prefix', noPrefix: true)]
    public function withoutPrefixAnnotations()
    {
    }
}
