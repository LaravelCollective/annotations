<?php

namespace Collective\Annotations\Database\Eloquent\Attributes\Attributes;

use Attribute;
use Collective\Annotations\Database\BindInterface;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Bind implements BindInterface
{
    public function __construct(
        protected string $binding,
        protected bool $digits = false,
        protected ?string $pattern = null,
    ) {}

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
