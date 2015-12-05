<?php

namespace Collective\Annotations\Database\Eloquent\Annotations\Annotations;

/**
 * @Annotation
 */
class Bind
{
    /**
     * The binding the annotation binds the model to.
     *
     * @var array
     */
    public $binding;

    /**
     * Create a new annotation instance.
     *
     * @param array $values
     *
     * @return void
     */
    public function __construct(array $values = [])
    {
        $this->binding = $values['value'];
    }
}
