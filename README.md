# Generate OpenAPI documentation for Laravel Applications

[![Latest Stable Version](https://poser.pugx.org/vyuldashev/laravel-openapi/v/stable?format=flat-square)](https://packagist.org/packages/vyuldashev/laravel-openapi)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/vyuldashev/laravel-openapi.svg?style=flat-square)](https://packagist.org/packages/vyuldashev/laravel-openapi)

## Installation

You can install the package via composer:

``` bash
composer require vyuldashev/laravel-openapi
```

The service provider will automatically get registered. Or you may manually add the service provider in your `config/app.php` file:

```php
'providers' => [
    // ...
    Vyuldashev\LaravelOpenApi\LaravelOpenApiServiceProvider::class,
];
```

Run `openapi:install` in order to install OpenApi service provider:

```bash
php artisan openapi:install
```

After running this command, verify that the App\Providers\OpenApiServiceProvider was added to the providers array in your app configuration file. If it wasn't, you should add it manually.
Of course, if your application does not use the App namespace, you should update the provider class name as needed. 
