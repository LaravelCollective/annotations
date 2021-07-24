<?php

namespace App\Http\Controllers\Attributes;

use Collective\Annotations\Routing\Attributes\Attributes\Any;

class AnyController
{
    #[Any(path: "my-any-route")]
    public function anyAnnotations()
    {
    }
}
