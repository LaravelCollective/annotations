<?php

namespace App\Http\Controllers;

use Collective\Annotations\Routing\Attributes\Attributes\Any;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Where;

class WhereController
{
    /**
     * @Any("/")
     * @Where(key="value")
     */
    #[Any(path: '/')]
    #[Where(['key' => 'value'])]
    public function whereAnnotations()
    {
    }

    /**
     * @Get("/{key}", where={"key": "value"})
     */
    #[Get(path: '/{key}', where: ['key' => 'value'])]
    public function getWhereAnnotations()
    {
    }
}
