<?php

namespace App\Http\Controllers;

/**
 * @Controller(prefix="/any-prefix")
 */
class PrefixController
{
    /**
     * @Get("route-with-prefix", as="route.with.prefix")
    */
    public function withPrefixAnnotations()
    {
    }

    /**
     * @Get("route-without-prefix", as="route.without.prefix", no_prefix="true")
    */
    public function withoutPrefixAnnotations()
    {
    }
}
