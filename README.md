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

### ENV

By default, the post, put and delete routes are configured with Laravel Auth middleware.

To disable it, just add the variable ```GUX_AUTH=false``` in the .env file of laravel / lumen

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

Write permission for apache. Ex:

```sh
    chown -R $user:www-data storage/
```

Create a symbolic link between the app folder inside storage and the public folder. Exemplo:

```sh
    ln -s /var/www/fng-dev/categories/storage/app /var/www/fng-dev/categories/public
```
