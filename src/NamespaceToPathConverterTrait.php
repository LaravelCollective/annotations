<?php

namespace Collective\Annotations;

use Illuminate\Console\DetectsApplicationNamespace;

trait NamespaceToPathConverterTrait
{
    use DetectsApplicationNamespace;

    /**
     * Convert the given namespace to a file path.
     *
     * @param string $namespace the namespace to convert
     *
     * @return string
     */
    public function getPathFromNamespace($namespace, $base = null)
    {
        $appNamespace = $this->getAppNamespace();

        // remove the app namespace from the namespace if it is there
        if (substr($namespace, 0, strlen($appNamespace)) == $appNamespace) {
            $namespace = substr($namespace, strlen($appNamespace));
        }

        $path = str_replace('\\', '/', trim($namespace, ' \\'));

        // trim and return the path
        return ($base ?: app_path()).'/'.$path;
    }
}
