<?php

namespace App\Http\Controllers\Attributes;

use Collective\Annotations\Routing\Attributes\Attributes\Get;

class ChildController extends AnyController
{
    #[Get(path: '/child/{id}')]
    public function childMethodAnnotations($id)
    {
    }
}
