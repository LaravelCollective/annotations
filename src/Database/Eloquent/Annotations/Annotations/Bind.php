<?php

namespace Collective\Annotations\Database\Eloquent\Annotations\Annotations;

use Collective\Annotations\Database\BindInterface;

/**
 * @Annotation
 */
class Bind implements BindInterface
{
    /**
     * The binding the annotation binds the model to.
     *
     * @var array
     */
    protected $binding;

    /**
     * Whether to use a digits pattern.
     *
     * @var bool
     */
    protected $digits;

    /**
     * The pattern to use.
     *
     * @var string
     */
    protected $pattern;

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
        $this->digits = $values['digits'] ?? false;
        $this->pattern = $values['pattern'] ?? null;
    }

    /**
     * @return string
     */
    public function getKey() {
        return $this->binding;
    }

    /**
     * @return string
     */
    public function getPattern() {
        if ($this->digits) {
            return '\d+';
        }

        return $this->pattern;
    }
}
