## Annotations for The Laravel Framework

[![Build Status](https://travis-ci.org/LaravelCollective/annotations.svg)](https://travis-ci.org/LaravelCollective/annotations)
[![Total Downloads](https://poser.pugx.org/LaravelCollective/annotations/downloads.svg)](https://packagist.org/packages/laravelcollective/annotations)
[![Latest Stable Version](https://poser.pugx.org/LaravelCollective/annotations/v/stable.svg)](https://packagist.org/packages/laravelcollective/annotations)
[![Latest Unstable Version](https://poser.pugx.org/LaravelCollective/annotations/v/unstable.svg)](https://packagist.org/packages/laravelcollective/annotations)
[![License](https://poser.pugx.org/LaravelCollective/annotations/license.svg)](https://packagist.org/packages/laravelcollective/annotations)

> During its early stages of development, Laravel 5.0 was gearing up to support Route and Event annotations. With much [controversy](http://www.laravelpodcast.com/episodes/6257-episode-18-the-war-over-php-annotations) and [discussion](https://laracasts.com/discuss/channels/general-discussion/route-annotation-in-laravel-5) on the matter, @taylorotwell decided to remove Annotation support from the core in favor of extracting Laravel Annotation Support to a third-party package. The result of this decision resulted in this package being maintained by a huge fan of Laravel Annotations.

## Installation

> If you have changed the top-level namespace to something like 'MyCompany', then you would use the new namespace instead of 'App'.

Begin by installing this package through Composer. Edit your project's `composer.json` file to require `laravelcollective/annotations`.

    "require": {
        "laravelcollective/annotations": "~5.0"
    }

Next, update Composer from the Terminal:

    composer update

Once composer is done, you'll need to create a Service Provider in `app/Providers/AnnotationsServiceProvider.php`.

```php
<?php namespace App\Providers;

use Collective\Annotations\AnnotationsServiceProvider as ServiceProvider;

class AnnotationsServiceProvider extends ServiceProvider {

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
     * Determines whether or not to automatically scan the controllers
     * directory (App\Http\Controllers) for routes
     *
     * @var bool
     */
    protected $scanControllers = false;

}
```

Finally, add your new provider to the `providers` array of `config/app.php`:

```php
  'providers' => [
    // ...
    'App\Providers\AnnotationsServiceProvider',
    // ...
  ];
```

## Usage

### Setting up Scanning

Scanning your controllers for annotations can be configured by editing the `protected $scanEvents` and `protected $scanRoutes` in your `AnnotationsServiceProvider`. For example, if you wanted to scan `App\Handlers\Events\MailHandler` for event annotations, you would add it to `protected $scanEvents` like so:

```php
    /**
     * The classes to scan for event annotations.
     *
     * @var array
     */
    protected $scanEvents = [
      'App\Handlers\Events\MailHandler',
    ];
```

Likewise, if you wanted to scan `App\Http\Controllers\HomeController` for route annotations, you would add it to `protected $scanRoutes` like so:

```php
    /**
     * The classes to scan for route annotations.
     *
     * @var array
     */
    protected $scanRoutes = [
      'App\Http\Controllers\HomeController',
    ];
```

Scanning your event handlers and controllers can be done manually by using `php artisan event:scan` and `php artisan route:scan` respectively, or automatically by setting `protected $scanWhenLocal = true`.

### Event Annotations

#### @Hears

The `@Hears` annotation registers an event listener for a particular event. Annotating any method with `@Hears("SomeEventName")` will register an event listener that will call that method when the `SomeEventName` event is fired.

```php
<?php namespace App\Handlers\Events;

use App\User;

class MailHandler {

  /**
   * Send welcome email to User
   * @Hears("UserWasRegistered")
   */
  public function sendWelcomeEmail(User $user)
  {
    // send welcome email to $user
  }

}
```

### Route Annotations

#### @Get

The `@Get` annotation registeres a route for an HTTP GET request.

```php
<?php namespace App\Http\Controllers;

class HomeController {

  /**
   * Show the Index Page
   * @Get("/")
   */
  public function getIndex()
  {
    return view('index');
  }

}
```

You can also set up route names.

```php
  /**
   * @Get("/", as="index")
   */
```

... or middlewares.

```php
  /**
   * @Get("/", middleware="guest")
   */
```

... or both.

```php
  /**
   * @Get("/", as="index", middleware="guest")
   */
```

Here's an example that uses all of the available parameters for a `@Get` annotation:

```php
  /**
   * @Get("/profiles/{id}", as="profiles.show", middleware="guest", domain="foo.com", where={"id": "[0-9]+"})
   */
```

#### @Post, @Options, @Put, @Patch, @Delete, @Any

The `@Post`, `@Options`, `@Put`, `@Patch`, `@Delete` and `@Any` annotations have the exact same syntax as the `@Get` annotation, except it will register a route for the respective HTTP verb, as opposed to the GET verb.

#### @Middleware

As well as defining middleware inline in the route definition tags (`@Get`, `@Post`, etc.), the `@Middleware` tag can be used on its own. It works both individual methods:

```php
  /**
   * Show the Login Page
   *
   * @Get("login")
   * @Middleware("guest")
   */
  public function login()
  {
    return view('index');
  }
```

Or on a whole controller, with the same only/exclude filter syntax that you can use elsewhere in laravel:

```php
/**
 * @Middleware("guest", except={"logout"})
 */
class AuthController extends Controller {

  /**
   * Log the user out.
   *
   * @Get("logout", as="logout")
   * @Middleware("auth")
   *
   * @return Response
   */
  public function logout()
  {
    $this->auth->logout();

    return redirect( route('login') );
  }

}

```

#### Scan the Controllers Directory

To recursively scan the entire controllers namespace ( `App\Http\Controllers` ), you can set the `$scanControllers` flag to true.

It will automatically adjust `App` to your app's namespace.

```php
    $scanControllers = true;
```

### Advanced

If you want to use any logic to add classes to the list to scan, you can override the `routeScans()` or `eventScans()` methods.

The following is an example of adding a controller to the scan list if the current environment is `local`:

```php
public function routeScans() {
    $classes = parent::routeScans();

    if ( $this->app->environment('local') ) {
        $classes = array_merge($classes, ['App\\Http\\Controllers\\LocalOnlyController']);
    }

    return $classes;
}
```

#### Scanning Namespaces

You can use the `getClassesFromNamespace( $namespace )` method to recursively add namespaces to the list. This will scan the given namespace. It only works for classes in the `app` directory, and relies on the PSR-4 namespacing standard.

This is what the `$scanControllers` flag uses with the controllers directory.

Here is an example that builds on the last one - adding a whole local-only namespace.

```php
public function routeScans() {
    $classes = parent::routeScans();

    if ( $this->app->environment('local') ) {
    {
        $classes = array_merge(
            $classes,
            $this->getClassesFromNamespace( 'App\\Http\\Controllers\\Local' )
        );
    }

    return $classes;
}
```

## Custom Annotations

If you want to register your own annotations, create a namespace containing subclasses of `Collective\Annotations\Routing\Annotations\Annotations\Annotation` - let's say `App\Http\Annotations`.

Then, in your annotations service provider, override the `addRoutingAnnotations( RouteScanner $scanner )` method, and register your routing annotations namespace:

```php
<?php namespace App\Providers;

use Collective\Annotations\Routing\Annotations\Scanner as RouteScanner;

/* ... then, in the class definition ... */

    /**
     * Add annotation classes to the route scanner
     *
     * @param RouteScanner $namespace
     */
    public function addRoutingAnnotations( RouteScanner $scanner )
    {
      $scanner->addAnnotationNamespace( 'App\Http\Annotations' );
    }
```

Your annotation classes must must have the `@Annotation` class annotation (see the following example).

There is an equivalent method for event annotations: `addEventAnnotations( EventScanner $scanner )`.

### Custom Annotation Example

Here is an example to make an `@Auth` annotation. It provides the same functionality as using the annotation `@Middleware("auth")`.

In a namespace - in this example, `App\Annotations`:

```php
<?php namespace App\Http\Annotations;

use Collective\Annotations\Routing\Annotations\Annotations\Annotation;
use Collective\Annotations\Routing\Annotations\MethodEndpoint;
use ReflectionMethod;

/**
 * @Annotation
 */
class Auth extends Annotation {

  /**
   * {@inheritdoc}
   */
  public function modify(MethodEndpoint $endpoint, ReflectionMethod $method)
  {
    if ($endpoint->hasPaths())
    {
      foreach ($endpoint->getPaths() as $path)
      {
        $path->middleware = array_merge($path->middleware, (array) 'auth');
      }
    }
    else
    {
      $endpoint->middleware = array_merge($endpoint->middleware, (array) 'auth');
    }
  }

}
```
