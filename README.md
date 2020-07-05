# Logger

## Class Features
- Integration with laravel / lumen
- Automatic table creation in database
- Automatic route configurations

## Installation

```sh
composer require fng-dev/category-base
```

## Configs

### Lumen

Uncomment the lines

```sh
$app->routeMiddleware([
    'auth' => App\Http\Middleware\Authenticate::class,
]);
```
and

```sh
$app->register(App\Providers\AuthServiceProvider::class);
```

and add

```sh
$app->register(Fng\CategoryBase\CategoryServiceProvider::class);
```
below the last register inside the file

```sh
    bootstrap/app.php
```

## Migration

Run migrations command

```sh
    php artisan migration
```


