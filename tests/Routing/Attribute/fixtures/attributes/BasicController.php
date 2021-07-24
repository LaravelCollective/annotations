<?php

namespace App\Http\Controllers\Attributes;


use Collective\Annotations\Routing\Attributes\Attributes\Controller;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Put;
use Collective\Annotations\Routing\Attributes\Attributes\Resource;
use Collective\Annotations\Routing\Attributes\Attributes\Where;

#[Resource(name: 'foobar/photos', only: ['index', 'update'], names: ['index' => 'index.name'])]
#[Controller(domain: '{id}.account.com')]
#[Middleware(name: 'FooMiddleware')]
#[Middleware(name: 'BarMiddleware')]
#[Middleware(name: 'BoomMiddleware', options: ['only' => ['index']])]
#[Where(['id'=> 'regex'])]
class BasicController
{
    #[Middleware(name: 'BazMiddleware')]
    public function index()
    {
    }

    public function update($id)
    {
    }

    #[Put(path: '/more/{id}', after: 'log')]
    #[Middleware(name: 'QuxMiddleware')]
    public function doMore($id)
    {
    }
}
