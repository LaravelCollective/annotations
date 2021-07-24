<?php

namespace App\Attributes;

use Collective\Annotations\Database\Eloquent\Attributes\Attributes\Bind;
use Illuminate\Database\Eloquent\Model as Eloquent;

#[Bind('users')]
class User extends Eloquent
{
}
