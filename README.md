# devhammed/server-actions

[![Latest Version on Packagist](https://img.shields.io/packagist/v/devhammed/server-actions.svg?style=flat-square)](https://packagist.org/packages/devhammed/server-actions)

Making [Next.js Server Actions](https://nextjs.org/docs/app/api-reference/functions/server-actions) work in PHP!

This is a proof of concept inspired by [this tweet](https://x.com/WebReflection/status/1717853489034932631?s=20), and it's not meant to be used in production.

But it works so let's go!

## Installation

The recommended way to install this package is through [Composer](https://getcomposer.org/).

```bash
composer require devhammed/server-actions
```

Then include the Composer autoloader in your entry file (e.g `index.php`) like:

```php
<?php

// File: /public/index.php

require_once __DIR__ . '/../vendor/autoload.php';
```

But you can also download this repository and include the provided `autoload.php` file e.g

```php
require_once __DIR__ . '/libs/server-actions/autoload.php';
```

## Usage

You can use this package in any PHP project, but it has first-class support for [Laravel](https://laravel.com) and all the heavy lifting is done for you.

You can skip to the [Laravel](#laravel) section if you are using Laravel.

The basic concept is that you have to initialize the Server in your entry file (e.g `index.php`) before using it by calling the `useServer()` function 
without any arguments which will return the instance that you can use to configure the server  endpoint and the storage for the server actions which the package currently provides
[JsonFileServerEntry](./src/Concerns/JsonFileServerEntry.php) that stores serialized actions in a JSON file, you can implement [DevHammed\ServerActions\Contracts\ServerEntry](./src/Contracts/ServerEntry.php) interface.

```php
<?php

// File: /public/index.php

require_once __DIR__ . '/vendor/autoload.php';

use DevHammed\ServerActions\Concerns\JsonFileServerEntry;

use function DevHammed\ServerActions\useServer;

useServer()
    ->withServerActionsUrl('/server-actions.php')
    ->withServerEntry(
       new JsonFileServerEntry(
           __DIR__ . '/server-actions.json',
       ),
    );

// Other logic to include your PHP files for the request here...
```

Then you need to create the server actions handler file that was specified in the `withServerActionsUrl` method e.g `server-actions.php` that will look
almost the same as the entry file but with the `run()` method called at the end of the chain and nothing else, like:

```php
<?php

// File: /public/server-actions.php

require __DIR__ . '/../vendor/autoload.php';

use DevHammed\ServerActions\Concerns\JsonFileServerEntry;

use function DevHammed\ServerActions\useServer;

useServer()
	->withServerActionsUrl('/server-actions.php')
	->withServerEntry(
		new JsonFileServerEntry(
			__DIR__ . '/server-actions.json',
		),
	)
	->run();
```

Then you can use the `useServer` helper function in the included PHP files like:

```php
<?php
    // File: /views/my-form.php

    use function DevHammed\ServerActions\useServer;
?>

<form
    method="post"
    action="<?= useServer(function (string $name) {
            echo 'Hello, ' . $name;
    }) ?>"
>
    <input type="hidden" name="name" value="Hammed">
    <button type="submit">Greet the creator</button>
</form>
```

### Laravel

This package provides a service provider for Laravel that will automatically initialize the server and endpoint for you.

You can register the service provider in your `config/app.php` file if you are using Laravel 5.4 or below, but if you are using Laravel 5.5 or above, the package will automatically register itself using auto-discovery.

```php
<?php

// File: config/app.php

return [
    // ...
    'providers' => [
        // ...
        DevHammed\ServerActions\ServerActionsProvider::class,
    ],
    // ...
];
```

After that, you should run the following command to publish the configuration file to `config/server-actions.php` and setup other things that might be needed.

```bash
php artisan server-actions:install
```

Then you can use the `useServer` helper function in your Blade templates like:

```php
<?php
    // File: /resources/views/my-form.blade.php

    use function DevHammed\ServerActions\useServer;
?>

<form
    method="post"
    action="<?= useServer(function (string $name) {
            return 'Hello, ' . $name;
    }) ?>"
>
    @csrf
    <input type="hidden" name="name" value="Hammed">
    <button type="submit">Greet the creator</button>
</form>
```

Note that the `@csrf` directive is required for Laravel to accept the request since this is like
every other form request and the handler can be used just like you would use a controller method e.g redirecting, returning a view, etc.
which is why we are returning a string instead of echoing it unlike the vanilla PHP example.

That's it! Go make some server actions!

## License

This package is open-sourced software licensed under the [MIT license](LICENSE.md).

## Credits

- [Hammed Oyedele](https://github.com/devhammed)