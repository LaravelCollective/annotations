<?php

namespace App\Http\Controllers;

class AnyController extends BasicController
{
    /**
     * @Any("my-any-route")
     */
    public function anyAnnotations()
    {
    }
}
