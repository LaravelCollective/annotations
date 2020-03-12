<?php

namespace Collective\Annotations;

use Illuminate\Container\Container;
use Illuminate\Support\Facades\App;

trait NamespaceToPathConverterTrait
{

    /**
     * Convert the given namespace to a file path.
     *
     * @param string $namespace the namespace to convert
     *
     * @return string
     */
    public function getPathFromNamespace($namespace, $base = null)
    {
        $appNamespace = Container::getInstance()->getNamespace();

        // remove the app namespace from the namespace if it is there
        if (substr($namespace, 0, strlen($appNamespace)) == $appNamespace) {
            $namespace = substr($namespace, strlen($appNamespace));
        }

        $path = str_replace('\\', '/', trim($namespace, ' \\'));

        // trim and return the path
        return ($base ?: App::make('path')).'/'.$path;
    }
}
