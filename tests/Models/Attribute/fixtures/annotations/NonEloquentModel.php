<?php

namespace App\Attributes;

use Collective\Annotations\Database\Eloquent\Attributes\Attributes\Bind;

#[Bind('systems')]
class NonEloquentModel
{
}
