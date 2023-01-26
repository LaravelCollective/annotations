## Annotations for The Laravel Framework

[![Build Status](https://travis-ci.org/LaravelCollective/annotations.svg)](https://travis-ci.org/LaravelCollective/annotations)
[![Total Downloads](https://poser.pugx.org/LaravelCollective/annotations/downloads)](https://packagist.org/packages/laravelcollective/annotations)
[![Latest Stable Version](https://poser.pugx.org/LaravelCollective/annotations/v/stable.svg)](https://packagist.org/packages/laravelcollective/annotations)
[![Latest Unstable Version](https://poser.pugx.org/LaravelCollective/annotations/v/unstable.svg)](https://packagist.org/packages/laravelcollective/annotations)
[![License](https://poser.pugx.org/LaravelCollective/annotations/license.svg)](https://packagist.org/packages/laravelcollective/annotations)

# Annotations

- [Installation](#installation)
- [Scanning](#scanning)
- [Event Annotations](#events)
- [Route Annotations](#routes)
- [Scanning Controllers](#controllers)
- [Model Annotations](#models)
- [Custom Annotations](#custom-annotations)

<a name="installation"></a>
## Installation

> If you have changed the top-level namespace to something like 'MyCompany', then you would use the new namespace instead of 'App'.

Begin by installing this package through Composer. Edit your project's `composer.json` file to require `laravelcollective/annotations`.

    "require": {
        "laravelcollective/annotations": "8.0.\*"
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
     * The classes to scan for model annotations.
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
     * directory (App\Http\Controllers) for routes
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
    
}
```

Finally, add your new provider to the `providers` array of `config/app.php`:

```php
  'providers' => [
    // ...
    App\Providers\AnnotationsServiceProvider::class
    // ...
  ];
```

This doesn't replace the `RouteServiceProvider`, this is still required as this handles loading of the route cache etc.

<a name="scanning"></a>
## Setting up Scanning

Add event handler classes to the `protected $scanEvents` array to scan for event annotations.

```php
    /**
     * The classes to scan for event annotations.
     *
     * @var array
     */
    protected $scanEvents = [
      App\Handlers\Events\MailHandler::class,
    ];
```

Add controllers to the `protected $scanRoutes` array to scan for route annotations.

```php
    /**
     * The classes to scan for route annotations.
     *
     * @var array
     */
    protected $scanRoutes = [
      App\Http\Controllers\HomeController::class,
    ];
```

Add models to the `protected $scanModels` array to scan for model annotations.

```php
    /**
     * The classes to scan for model annotations.
     *
     * @var array
     */
    protected $scanModels = [
      'App\User',
    ];
```

Or scan your entire models namespace:

```php
    /**
     * The namespace to scan for models in.
     *
     * @var string
     */
    protected $scanModelsInNamespace = 'App\Models';
```

Alternatively, you can set `protected $scanEverything` to `true` to automatically scan all classes within your application's namespace. *Note:* This may increase the time required to execute the scanners, depending on the size of your application.

Scanning your event handlers, controllers, and models can be done manually by using `php artisan event:scan`, `php artisan route:scan`, or `php artisan model:scan` respectively. In the local environment, you can scan them automatically by setting `protected $scanWhenLocal = true`.

<a name="events"></a>
## Event Annotations

### @Hears

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
or if you prefer to use attributes, set `$useAttribute` to `true` and do this. Note that unlike annotations, a use statement is required for the attribute.

```php
<?php namespace App\Handlers\Events;

use App\User;
use Collective\Annotations\Events\Attributes\Attributes\Hears;

class MailHandler {

  #[Hears('UserWasRegistered')]
  public function sendWelcomeEmail(User $user)
  {
    // send welcome email to $user
  }

}
```

<a name="routes"></a>
## Route Annotations

Route annotations can be incredibly powerful, however the order of your route definitions can impact how your application matches specific routes, specifically any wildcard routes. If `protected $scanEverything` is set to `true`, you will have no control over the order of your route definitions.

### @Get

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

   #[Get(path: '/', as: 'index')]
```

... or middlewares.

```php
  /**
   * @Get("/", middleware="guest")
   */

   #[Get(path: '/', middleware: 'guest')]
```

... or both.

```php
  /**
   * @Get("/", as="index", middleware="guest")
   */

   #[Get(path: '/', as: 'index', middleware: 'guest')]
```

Here's an example that uses all of the available parameters for a `@Get` annotation:

```php
  /**
   * @Get("/profiles/{id}", as="profiles.show", middleware="guest", domain="foo.com", where={"id": "[0-9]+"}, no_prefix="true")
   */

   #[Get(path: '/profiles/{id}', as: 'profiles.show', middleware: 'guest', domain: 'foo.com', where: ['id' => '[0-9]+'], noPrefix: true)]
```
`no_prefix` allows any prefix added to the routes in that controller be ignored for this particular route.

### @Post, @Options, @Put, @Patch, @Delete, @any

The `@Post`, `@Options`, `@Put`, `@Patch`, `@Delete`, and `@Any` annotations have the exact same syntax as the `@Get` annotation, except it will register a route for the respective HTTP verb, as opposed to the GET verb.

### @Middleware

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

  #[Get(path: 'login')]
  #[Middleware(name: 'guest')]
  public function login()
  {
    return view('index');
  }
```

Or on a whole controller, with the same only/exclude filter syntax that you can use elsewhere in laravel:

```php
/**
 * @Middleware("guest", except={"logout"}, prefix="/your/prefix")
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

#[Middleware(name: 'guest', except: ['logout'], prefix: '/your/prefix')]
class AuthController extends Controller {

  #[Get(path: 'logout', as: 'logout')]
  #[Middleware(name: 'auth')]
  public function logout()
  {
    $this->auth->logout();

    return redirect( route('login') );
  }

}
```

### @Resource

Using the `@Resource` annotation on a controller allows you to easily set up a Resource Controller.

```php
<?php
/**
 * @Resource('users')
 */
class UsersController extends Controller {
  // index(), create(), store(), show($id), edit($id), update($id), destroy($id)
}
```

You can specify the `only` and `except` arguments, just like you can with the regular `Route::resource()` command.

```php
  /**
   * @Resource('users', only={"index", "show"})
   */

   #[Resource('users', only: ['index', 'show'])]
```

You can also specify the route names of each resource method.

```php
  /**
   * @Resource('users', names={"index"="user.all", "show"="user.view"})
   */

   #[Resource('users', names: ['index' => 'user.all', 'show' => 'user.view'])]
```

### @Controller

Using the `@Controller` annotation on a controller allows you to set various options for the routes contained in it:

```php
<?php
/**
 * @Controller(prefix="admin", domain="foo.com")
 */
class AdminController extends Controller {
  // All routes will be prefixed by admin/
}

#[Controller(prefix: 'admin', domain: 'foo.com')]
class AdminController extends Controller {
  // All routes will be prefixed by admin/
}
```

<a name="controllers"></a>
## Scan the Controllers Directory

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
        $classes = array_merge($classes, [App\Http\Controllers\LocalOnlyController::class]);
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
        $classes = array_merge(
            $classes,
            $this->getClassesFromNamespace( App\Http\Controllers\Local::class )
        );
    }

    return $classes;
}
```

<a name="models"></a>
## Model Annotations

You can use annotations to automatically bind your models to route parameters, using [Route Model Binding](http://laravel.com/docs/5.8/routing#route-model-binding). To do this, use the `@Bind` annotation.

```php
/**
 * @Bind("users")
 */
class User extends Eloquent {
  //
}

#[Bind('users')]
class User extends Eloquent {
  //
}
```

This is the equivalent of calling `Route::model('users', 'App\Users')`.

<a name="custom-annotations"></a>
## Custom Annotations
_Please Note: the namespaces have been updated in version 8.1 in order to allow PHP 8 attribute support._

If you want to register your own annotations, create a namespace containing subclasses of `Collective\Annotations\Routing\Meta` - let's say `App\Http\Annotations`.\
(_For version 8.0 and older, extend `Collective\Annotations\Routing\Annotations\Annotations\Annotation`_)

Then, in your annotations service provider, override the `addRoutingAnnotations( RouteScanner $scanner )` method, and register your routing annotations namespace:

```php
<?php namespace App\Providers;

use Collective\Annotations\Routing\Scanner as RouteScanner;
# For version 8.0 and older use this instead of the above:
# use Collective\Annotations\Routing\Annotations\Scanner as RouteScanner;

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

Your annotation classes must have the `@Annotation` class annotation (see the following example).

There is an equivalent method for event annotations: `addEventAnnotations( EventScanner $scanner )`.

### Custom Annotation Example

Here is an example to make an `@Auth` annotation. It provides the same functionality as using the annotation `@Middleware("auth")`.

In a namespace - in this example, `App\Annotations`:

```php
<?php namespace App\Http\Annotations;

use Collective\Annotations\Routing\Meta;
# For version 8.0 and older use this instead of the above:
# use Collective\Annotations\Routing\Annotations\Annotations\Annotation;
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
