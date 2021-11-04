<?php
namespace Collective\Annotations;

use Illuminate\Container\Container;

/**
 * Trait DetectsApplicationNamespace
 *
 * This got deprecated in Laravel 7, so fastest solution would be to just have this here.
 *
 * @package Collective\Annotations
 */
trait DetectsApplicationNamespace
{
    /**
     * Get the application namespace.
     *
     * @return string
     */
    protected function getAppNamespace()
    {
        return Container::getInstance()->getNamespace();
    }
}
