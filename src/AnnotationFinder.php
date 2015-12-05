<?php

namespace Collective\Annotations;

use Illuminate\Contracts\Foundation\Application;

class AnnotationFinder
{
    /**
     * @var Application
     */
    private $app;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Determine if the application routes have been scanned.
     *
     * @return bool
     */
    public function routesAreScanned()
    {
        return $this->app['files']->exists($this->getScannedRoutesPath());
    }

    /**
     * Get the path to the scanned routes file.
     *
     * @return string
     */
    public function getScannedRoutesPath()
    {
        return $this->app['path.storage'].'/framework/routes.scanned.php';
    }

    /**
     * Determine if the application events have been scanned.
     *
     * @return bool
     */
    public function eventsAreScanned()
    {
        return $this->app['files']->exists($this->getScannedEventsPath());
    }

    /**
     * Get the path to the scanned events file.
     *
     * @return string
     */
    public function getScannedEventsPath()
    {
        return $this->app['path.storage'].'/framework/events.scanned.php';
    }

    /**
     * Determine if the application models have been scanned.
     *
     * @return string
     */
    public function modelsAreScanned()
    {
        return $this->app['files']->exists($this->getScannedModelsPath());
    }

    /**
     * Get the path to the scanned models file.
     *
     * @return string
     */
    public function getScannedModelsPath()
    {
        return $this->app['path.storage'].'/framework/models.scanned.php';
    }
}
