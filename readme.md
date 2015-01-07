## Annotations for The Laravel Framework

[![Build Status](https://travis-ci.org/adamgoose/laravel-annotations.svg)](https://travis-ci.org/adamgoose/laravel-annotations)
[![Total Downloads](https://poser.pugx.org/adamgoose/laravel-annotations/downloads.svg)](https://packagist.org/packages/adamgoose/laravel-annotations)
[![Latest Stable Version](https://poser.pugx.org/adamgoose/laravel-annotations/v/stable.svg)](https://packagist.org/packages/adamgoose/laravel-annotations)
[![Latest Unstable Version](https://poser.pugx.org/adamgoose/laravel-annotations/v/unstable.svg)](https://packagist.org/packages/adamgoose/laravel-annotations)
[![License](https://poser.pugx.org/adamgoose/laravel-annotations/license.svg)](https://packagist.org/packages/adamgoose/laravel-annotations)

During its early stages of development, Laravel 5.0 was gearing up to support Route and Event annotations. With much [contraversy](http://www.buzzsprout.com/11908/212256-episode-18-the-war-over-php-annotations) and [discussion](https://laracasts.com/discuss/channels/general-discussion/route-annotation-in-laravel-5) on the matter, @taylorotwell decided to remove Annotation support from the core in favor of extracting Laravel Annotation Support to a third-party package. The result of this decision resulted in this package being maintained by a huge fan of Laravel Annotations.
 
## Installation
 
Begin by installing this package through Composer. Edit your project's `composer.json` file to require `adamgoose/laravel-annotations`.

    "require": {
        "adamgoose/laravel-annotations": "~5.0"
    }
    
Next, update Composer from the Terminal:

    composer update
    
Once composer is done, you'll need to create a Service Provider in `app/Providers/AnnotationsServiceProvider.php`.

```
<?php namespace App\Providers;

use Adamgoose\AnnotationsServiceProvider as ServiceProvider;

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

Coming soon...