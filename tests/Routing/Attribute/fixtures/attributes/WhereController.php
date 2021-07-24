<?php

namespace App\Http\Controllers\Attributes;

use Collective\Annotations\Routing\Attributes\Attributes\Any;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Where;

class WhereController
{
    #[Any(path: '/')]
    #[Where(['key' => 'value'])]
    public function whereAnnotations()
    {
    }

    #[Get(path: '/{key}', where: ['key' => 'value'])]
    public function getWhereAnnotations()
    {
    }
}
