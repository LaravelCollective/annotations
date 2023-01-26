<?php

namespace Collective\Annotations\Database;

interface BindInterface
{
    /**
     * @return string
     */
    public function getKey();

    /**
     * @return string
     */
    public function getPattern();

}
