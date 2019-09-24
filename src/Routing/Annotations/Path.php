<?php

namespace Collective\Annotations\Routing\Annotations;

class Path extends AbstractPath
{
    /**
     * The name of the route.
     *
     * @var string
     */
    public $as;

    /**
     * Should the prefix added to the controller be ignored for this particular route
     *
     * @var bool
     */
    public $no_prefix;

    /**
     * Create a new Route Path instance.
     *
     * @param string $verb
     * @param string $domain
     * @param string $path
     * @param string $as
     * @param array  $middleware
     * @param array  $where
     *
     * @return void
     */
    public function __construct($verb, $domain, $path, $as, $middleware = [], $where = [], $no_prefix = false)
    {
        $this->as = $as;
        $this->verb = $verb;
        $this->where = $where;
        $this->domain = $domain;
        $this->middleware = $middleware;
        $this->path = $path == '/' ? '/' : trim($path, '/');
        $this->no_prefix = $no_prefix;
    }
}