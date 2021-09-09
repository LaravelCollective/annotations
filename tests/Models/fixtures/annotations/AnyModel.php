<?php

namespace App;

use Collective\Annotations\Database\Eloquent\Attributes\Attributes\Bind;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * @Bind("users")
 */
#[Bind('users')]
class User extends Eloquent
{
}
