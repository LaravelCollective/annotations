<?php

namespace App\Http\Controllers;

class ChildController extends AnyController
{
    /**
     * @Get("/child/{id}")
     */
    public function childMethodAnnotations($id)
    {
    }
}
