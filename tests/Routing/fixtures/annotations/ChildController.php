<?php

namespace App\Http\Controllers;

use Collective\Annotations\Routing\Attributes\Attributes\Get;

class ChildController extends AnyController
{
    /**
     * @Get("/child/{id}")
     */
    #[Get(path: '/child/{id}')]
    public function childMethodAnnotations($id)
    {
    }
}
