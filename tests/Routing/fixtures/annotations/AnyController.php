<?php

namespace App\Http\Controllers;

use Collective\Annotations\Routing\Attributes\Attributes\Any;

class AnyController
{
    /**
     * @Any("my-any-route")
     */
    #[Any(path: "my-any-route")]
    public function anyAnnotations()
    {
    }
}
