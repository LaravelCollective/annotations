<?php namespace Collective\Annotations;

use Collective\Annotations\Console\EventScanCommand;
use Collective\Annotations\Console\RouteScanCommand;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Collective\Annotations\Events\Annotations\Scanner as EventScanner;
use Collective\Annotations\Routing\Annotations\Scanner as RouteScanner;

class AnnotationsServiceProvider extends ServiceProvider {

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
      'EventScan' => 'command.event.scan',
      'RouteScan' => 'command.route.scan',
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
     * Determines if we will auto-scan in the local environment.
     *
     * @var bool
     */
    protected $scanWhenLocal = false;

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
        $this->registerCommands();
    }

    /**
     * Register the application's annotated event listeners.
     *
     * @return void
     */
    public function boot()
    {
        $this->addEventAnnotations( $this->app->make('annotations.event.scanner') );

        $this->loadAnnotatedEvents();

        $this->addRoutingAnnotations( $this->app->make('annotations.route.scanner') );

        if ( ! $this->app->routesAreCached())
        {
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
        foreach (array_keys($this->commands) as $command)
        {
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
        $this->app->singleton('command.event.scan', function ($app)
        {
            return new EventScanCommand($app['files']);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerRouteScanCommand()
    {
        $this->app->singleton('command.route.scan', function ($app)
        {
            return new RouteScanCommand($app['files']);
        });
    }

    /**
     * Register the scanner.
     *
     * @return void
     */
    protected function registerRouteScanner()
    {
        $this->app->bindShared('annotations.route.scanner', function ($app)
        {
            $scanner = new RouteScanner([]);

            $scanner->addAnnotationNamespace(
                'Collective\Annotations\Routing\Annotations\Annotations',
                __DIR__.'/Routing/Annotations/Annotations'
            );

            return $scanner;
        });
    }

    /**
     * Register the scanner.
     *
     * @return void
     */
    protected function registerEventScanner()
    {
        $this->app->bindShared('annotations.event.scanner', function ($app)
        {
            $scanner = new EventScanner([]);

            $scanner->addAnnotationNamespace(
                'Collective\Annotations\Events\Annotations\Annotations',
                __DIR__.'/Events/Annotations/Annotations'
            );

            return $scanner;
        });
    }

    /**
     * Add annotation classes to the event scanner
     *
     * @param RouteScanner $namespace
     */
    public function addEventAnnotations( EventScanner $scanner ) {}

    /**
     * Add annotation classes to the route scanner
     *
     * @param RouteScanner $namespace
     */
    public function addRoutingAnnotations( RouteScanner $scanner ) {}

    /**
     * Load the annotated events.
     *
     * @return void
     */
    public function loadAnnotatedEvents()
    {
        if ($this->app->environment('local') && $this->scanWhenLocal)
        {
            $this->scanEvents();
        }

        $scans = $this->eventScans();

        if ( ! empty($scans) && $this->finder->eventsAreScanned())
        {
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

        if (empty($scans))
        {
            return;
        }

        $scanner = $this->app->make('annotations.event.scanner');

        $scanner->setClassesToScan($scans);

        file_put_contents(
          $this->finder->getScannedEventsPath(), '<?php ' . $scanner->getEventDefinitions()
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
     * Load the annotated routes
     *
     * @return void
     */
    protected function loadAnnotatedRoutes()
    {
        if ($this->app->environment('local') && $this->scanWhenLocal)
        {
            $this->scanRoutes();
        }

        $scans = $this->routeScans();

        if ( ! empty($scans) && $this->finder->routesAreScanned())
        {
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

        if (empty($scans))
        {
            return;
        }

        $scanner = $this->app->make('annotations.route.scanner');

        $scanner->setClassesToScan($scans);

        file_put_contents(
          $this->finder->getScannedRoutesPath(), '<?php ' . $scanner->getRouteDefinitions()
        );
    }

    /**
     * Load the scanned application routes.
     *
     * @return void
     */
    protected function loadScannedRoutes()
    {
        $this->app->booted(function ()
        {
            $router = $this->app['Illuminate\Contracts\Routing\Registrar'];

            require $this->finder->getScannedRoutesPath();
        });
    }

    /**
     * Get the classes to be scanned by the provider.
     *
     * @return array
     */
    public function eventScans()
    {
        return $this->scanEvents;
    }

    /**
     * Get the classes to be scanned by the provider.
     *
     * @return array
     */
    public function routeScans()
    {
        return $this->scanRoutes;
    }
}
