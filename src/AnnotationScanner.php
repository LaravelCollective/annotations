<?php

namespace Collective\Annotations;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\SimpleAnnotationReader;
use Symfony\Component\Finder\Finder;

abstract class AnnotationScanner extends BaseScanner
{
    use NamespaceToPathConverterTrait;

    /**
     * Namespaces to check for annotation reader annotation classes.
     *
     * @var array
     */
    protected $namespaces = [];

    /**
     * Add an annotation namespace for the SimpleAnnotationReader instance.
     *
     * If the second parameter is null, it will assume the namespace is PSR-4'd
     * inside your app folder.
     *
     * @param string $namespace
     * @param string $path
     */
    public function addAnnotationNamespace($namespace, $path = null)
    {
        $this->namespaces[] = $namespace;

        return $this->registerAnnotationsPathWithRegistry(
            $path ?: $this->getPathFromNamespace($namespace)
        );
    }

    /**
     * Register the annotator files with the annotation registry.
     *
     * @param string $path
     *
     * @return $this
     */
    public function registerAnnotationsPathWithRegistry($path)
    {
        foreach (Finder::create()->files()->in($path) as $file) {
            AnnotationRegistry::registerFile($file->getRealPath());
        }

        return $this;
    }

    /**
     * Get an annotation reader instance.
     *
     * @return \Doctrine\Common\Annotations\SimpleAnnotationReader
     */
    protected function getReader()
    {
        $reader = new SimpleAnnotationReader();

        foreach ($this->namespaces as $namespace) {
            $reader->addNamespace($namespace);
        }

        return $reader;
    }
}
