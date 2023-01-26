<?php

namespace Collective\Annotations;

use Collective\Annotations\Console\EventScanCommand;
use Collective\Annotations\Console\ModelScanCommand;
use Collective\Annotations\Console\RouteScanCommand;
use Collective\Annotations\Database\Eloquent\Annotations\AnnotationStrategy as ModelScanAnnotationStrategy;
use Collective\Annotations\Database\Eloquent\Attributes\AttributeStrategy as ModelScanAttributeStrategy;
use Collective\Annotations\Database\Scanner as ModelScanner;
use Collective\Annotations\Database\ScanStrategyInterface as ModelScanStrategy;
use Collective\Annotations\Events\Annotations\AnnotationStrategy as EventsScanAnnotationStrategy;
use Collective\Annotations\Events\Attributes\AttributeStrategy as EventsScanAttributeStrategy;
use Collective\Annotations\Events\Scanner as EventScanner;
use Collective\Annotations\Events\ScanStrategyInterface as EventsScanStrategy;
use Collective\Annotations\Routing\Annotations\AnnotationStrategy as RouteScanAnnotationStrategy;
use Collective\Annotations\Routing\Attributes\AttributeStrategy as RouteScanAttributeStrategy;
use Collective\Annotations\Routing\Scanner as RouteScanner;
use Collective\Annotations\Routing\ScanStrategyInterface as RouteScanStrategy;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AnnotationsServiceProvider extends ServiceProvider
{
    use DetectsApplicationNamespace;

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
      'EventScan' => 'command.event.scan',
      'RouteScan' => 'command.route.scan',
      'ModelScan' => 'command.model.scan',
    ];

    /**
     * The classes to scan for event annotations.
     *
     * @var array
     */
    protected $scanEvents = [];

    /**
     * The classes to scan for route annotations.
     *
     * @var array
     */
    protected $scanRoutes = [];

    /**
     * The classes to scan for model binding annotations.
     *
     * @var array
     */
    protected $scanModels = [];

    /**
     * The namespace to scan for models in.
     *
     * @var string
     */
    protected $scanModelsInNamespace = null;

    /**
     * Determines if we will auto-scan in the local environment.
     *
     * @var bool
     */
    protected $scanWhenLocal = false;

    /**
     * Determines whether or not to automatically scan the controllers
     * directory (App\Http\Controllers) for routes.
     *
     * @var bool
     */
    protected $scanControllers = false;

    /**
     * Determines whether or not to automatically scan all namespaced
     * classes for event, route, and model annotations.
     *
     * @var bool
     */
    protected $scanEverything = false;

    /**
     * Determines whether to use attributes for scanning.
     *
     * @var bool
     */
    protected $useAttribute = false;

    /**
     * File finder for annotations.
     *
     * @var AnnotationFinder
     */
    protected $finder;

    /**
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    public function __construct(Application $app)
    {
        $this->finder = new AnnotationFinder($app);
        parent::__construct($app);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerAnnotationStrategies();
        $this->determineStrategy();

        $this->registerRouteScanner();
        $this->registerEventScanner();
        $this->registerModelScanner();

        $this->registerCommands();
    }

    /**
     * Register the application's annotated event listeners.
     *
     * @return void
     */
    public function boot()
    {
        if (!$this->useAttribute()) {
            $this->addModelAnnotations($this->app->make(ModelScanAnnotationStrategy::class));
            $this->addEventAnnotations($this->app->make(EventsScanAnnotationStrategy::class));
            $this->addRoutingAnnotations($this->app->make(RouteScanAnnotationStrategy::class));
        }

        $this->loadAnnotatedModels();
        $this->loadAnnotatedEvents();
        if (!$this->app->routesAreCached()) {
            $this->loadAnnotatedRoutes();
        }
    }

    /**
     * Register the commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        foreach (array_keys($this->commands) as $command) {
            $method = "register{$command}Command";
            call_user_func_array([$this, $method], []);
        }
        $this->commands(array_values($this->commands));
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerEventScanCommand()
    {
        $this->app->singleton('command.event.scan', function ($app) {
            return new EventScanCommand($app['files'], $this);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerRouteScanCommand()
    {
        $this->app->singleton('command.route.scan', function ($app) {
            return new RouteScanCommand($app['files'], $this);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerModelScanCommand()
    {
        $this->app->singleton('command.model.scan', function ($app) {
            return new ModelScanCommand($app['files'], $this);
        });
    }

    protected function registerAnnotationStrategies()
    {
        $this->app->singleton(ModelScanAnnotationStrategy::class, function ($app) {
            $strategy = new ModelScanAnnotationStrategy();
            $strategy->addAnnotationNamespace(
                'Collective\Annotations\Database\Eloquent\Annotations\Annotations',
                __DIR__ . '/Database/Eloquent/Annotations/Annotations'
            );
            return $strategy;
        });

        $this->app->singleton(EventsScanAnnotationStrategy::class, function ($app) {
            $strategy = new EventsScanAnnotationStrategy();
            $strategy->addAnnotationNamespace(
                'Collective\Annotations\Events\Annotations\Annotations',
                __DIR__ . '/Events/Annotations/Annotations'
            );
            return $strategy;
        });

        $this->app->singleton(RouteScanAnnotationStrategy::class, function ($app) {
            $strategy = new RouteScanAnnotationStrategy();
            $strategy->addAnnotationNamespace(
                'Collective\Annotations\Routing\Annotations\Annotations',
                __DIR__ . '/Routing/Annotations/Annotations'
            );
            return $strategy;
        });
    }

    protected function determineStrategy()
    {
        $this->app->bind(ModelScanStrategy::class, $this->useAttribute()? ModelScanAttributeStrategy::class: ModelScanAnnotationStrategy::class);
        $this->app->bind(EventsScanStrategy::class, $this->useAttribute()? EventsScanAttributeStrategy::class: EventsScanAnnotationStrategy::class);
        $this->app->bind(RouteScanStrategy::class, $this->useAttribute()? RouteScanAttributeStrategy::class: RouteScanAnnotationStrategy::class);
    }

    /**
     * Register the scanner.
     *
     * @return void
     */
    protected function registerRouteScanner()
    {
        $this->app->singleton('annotations.route.scanner', RouteScanner::class);
    }

    /**
     * Register the scanner.
     *
     * @return void
     */
    protected function registerEventScanner()
    {
        $this->app->singleton('annotations.event.scanner', EventScanner::class);
    }

    /**
     * Register the scanner.
     *
     * @return void
     */
    protected function registerModelScanner()
    {
        $this->app->singleton('annotations.model.scanner', ModelScanner::class);
    }

    /**
     * Add annotation classes to the event scanner.
     *
     * @param EventsScanAnnotationStrategy $strategy
     */
    public function addEventAnnotations(EventsScanAnnotationStrategy $strategy)
    {
    }

    /**
     * Add annotation classes to the route scanner.
     *
     * @param RouteScanAnnotationStrategy $strategy
     */
    public function addRoutingAnnotations(RouteScanAnnotationStrategy $strategy)
    {
    }

    /**
     * Add annotation classes to the model scanner.
     *
     * @param ModelScanAnnotationStrategy $strategy
     */
    public function addModelAnnotations(ModelScanAnnotationStrategy $strategy)
    {
    }

    /**
     * Load the annotated events.
     *
     * @return void
     */
    public function loadAnnotatedEvents()
    {
        if ($this->app->environment('local') && $this->scanWhenLocal) {
            $this->scanEvents();
        }

        $scans = $this->eventScans();

        if (!empty($scans) && $this->finder->eventsAreScanned()) {
            $this->loadScannedEvents();
        }
    }

    /**
     * Scan the events for the application.
     *
     * @return void
     */
    protected function scanEvents()
    {
        $scans = $this->eventScans();

        if (empty($scans)) {
            return;
        }

        $scanner = $this->app->make('annotations.event.scanner');

        $scanner->setClassesToScan($scans);

        file_put_contents(
          $this->finder->getScannedEventsPath(), '<?php '.PHP_EOL.PHP_EOL.$scanner->getEventDefinitions().PHP_EOL
        );
    }

    /**
     * Load the scanned events for the application.
     *
     * @return void
     */
    protected function loadScannedEvents()
    {
        $events = $this->app['Illuminate\Contracts\Events\Dispatcher'];

        require $this->finder->getScannedEventsPath();
    }

    /**
     * Load the annotated routes.
     *
     * @return void
     */
    protected function loadAnnotatedRoutes()
    {
        if ($this->app->environment('local') && $this->scanWhenLocal) {
            $this->scanRoutes();
        }

        $scans = $this->routeScans();

        if (!empty($scans) && $this->finder->routesAreScanned()) {
            $this->loadScannedRoutes();
        }
    }

    /**
     * Scan the routes and write the scanned routes file.
     *
     * @return void
     */
    protected function scanRoutes()
    {
        $scans = $this->routeScans();

        if (empty($scans)) {
            return;
        }

        $scanner = $this->app->make('annotations.route.scanner');

        $scanner->setClassesToScan($scans);

        file_put_contents(
            $this->finder->getScannedRoutesPath(), '<?php '.PHP_EOL.PHP_EOL.$scanner->getRouteDefinitions().PHP_EOL
        );
    }

    /**
     * Load the scanned application routes.
     *
     * @return void
     */
    protected function loadScannedRoutes()
    {
        $this->app->booted(function () {
            $router = $this->app['Illuminate\Contracts\Routing\Registrar'];

            require $this->finder->getScannedRoutesPath();
        });
    }

    /**
     * Load the annotated models.
     *
     * @return void
     */
    protected function loadAnnotatedModels()
    {
        if ($this->app->environment('local') && $this->scanWhenLocal) {
            $this->scanModels();
        }

        $scans = $this->modelScans();

        if (!empty($scans) && $this->finder->modelsAreScanned()) {
            $this->loadScannedModels();
        }
    }

    /**
     * Scan the models and write the scanned models file.
     *
     * @return void
     */
    protected function scanModels()
    {
        $scans = $this->modelScans();

        if (empty($scans)) {
            return;
        }

        $scanner = $this->app->make('annotations.model.scanner');

        $scanner->setClassesToScan($scans);

        file_put_contents(
          $this->finder->getScannedModelsPath(), '<?php '.PHP_EOL.PHP_EOL.$scanner->getModelDefinitions().PHP_EOL
        );
    }

    /**
     * Load the scanned application models.
     *
     * @return void
     */
    protected function loadScannedModels()
    {
        $this->app->booted(function () {
            $router = $this->app['Illuminate\Contracts\Routing\Registrar'];

            require $this->finder->getScannedModelsPath();
        });
    }

    /**
     * Get the classes to be scanned by the provider.
     *
     * @return array
     */
    public function eventScans()
    {
        if ($this->scanEverything) {
            return $this->getAllClasses();
        }

        return $this->scanEvents;
    }

    /**
     * Get the classes to be scanned by the provider.
     *
     * @return array
     */
    public function routeScans()
    {
        if ($this->scanEverything) {
            return $this->getAllClasses();
        }

        $classes = array_unique($this->scanRoutes);

        // scan the controllers namespace if the flag is set
        if ($this->scanControllers) {
            $classes = array_merge(
              $classes,
              $this->getClassesFromNamespace($this->getAppNamespace().'Http\\Controllers')
            );
        }

        return $classes;
    }

    /**
     * Get the classes to be scanned by the provider.
     *
     * @return array
     */
    public function modelScans()
    {
        if ($this->scanEverything) {
            return $this->getAllClasses();
        }

        if ($this->scanModelsInNamespace) {
            return $this->getClassesFromNamespace($this->scanModelsInNamespace);
        }

        return $this->scanModels;
    }

    /**
     * Convert the given namespace to a file path.
     *
     * @param string $namespace the namespace to convert
     *
     * @return string
     */
    public function convertNamespaceToPath($namespace)
    {
        // remove the app namespace from the namespace if it is there
        $appNamespace = $this->getAppNamespace();

        if (substr($namespace, 0, strlen($appNamespace)) == $appNamespace) {
            $namespace = substr($namespace, strlen($appNamespace));
        }

        // trim and return the path
        return str_replace('\\', '/', trim($namespace, ' \\'));
    }

    /**
     * Get a list of the classes in a namespace. Leaving the second argument
     * will scan for classes within the project's app directory.
     *
     * @param string $namespace the namespace to search
     * @param null   $base
     *
     * @return array
     */
    public function getClassesFromNamespace($namespace, $base = null)
    {
        $directory = ($base ?: $this->app->make('path')).'/'.$this->convertNamespaceToPath($namespace);

        return $this->app->make('Collective\Annotations\Filesystem\ClassFinder')->findClasses($directory);
    }

    /**
     * Get a list of classes in the root namespace.
     *
     * @return array
     */
    protected function getAllClasses()
    {
        return $this->getClassesFromNamespace($this->getAppNamespace());
    }

    protected function useAttribute(): bool
    {
        return $this->useAttribute && PHP_MAJOR_VERSION >= 8;
    }
}
