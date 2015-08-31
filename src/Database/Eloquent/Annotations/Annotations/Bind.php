<?php namespace Collective\Annotations\Database\Eloquent\Annotations\Annotations;

/**
 * @Annotation
 */
class Bind {

    /**
     * The binding the annotation binds the model to.
     *
     * @var string
     */
    public $binding;

    /**
     * Optional class (and method) used to resolve the model instance.
     *
     * @var string
     */
    public $binder = null;

    /**
     * Create a new annotation instance.
     *
     * @param  array  $values
     * @return void
     */
    public function __construct(array $values = array())
    {
        $this->binding = $values['value'];
        if (isset($values['uses'])) {
            $this->binder = $values['uses'];
        }
    }

}
