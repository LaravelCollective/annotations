<?php

namespace App\Http\Controllers;

class WhereController
{
    /**
     * @Any("/")
     * @Where(key="value")
     */
    public function whereAnnotations()
    {
    }

    /**
     * @Get("/{key}", where={"key": "value"})
     */
    public function getWhereAnnotations()
    {
    }
}
