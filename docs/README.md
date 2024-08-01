# Introduction

## Installation

You can install the package via composer:

```bash
composer require tartanlegrand/laravel-openapi
```

The service provider will automatically get registered. Or you may manually add the service provider in your config/app.php file:

```bash
'providers' => [
    // ...
    Vyuldashev\LaravelOpenApi\OpenApiServiceProvider::class,
];
```

You can publish the config file with:

```bash
php artisan vendor:publish --provider="Vyuldashev\LaravelOpenApi\OpenApiServiceProvider" --tag="openapi-config"
```

## Additional information

Before starting using this package you need to be familiar with OpenAPI specification and it's terms.

Here are some useful links that will help to gain enough knowledge:
* [OpenAPI Specification](https://github.com/OAI/OpenAPI-Specification/blob/master/versions/3.0.2.md)
* [OpenAPI Map](https://openapi-map.apihandyman.io)
* [Swagger Editor](https://editor.swagger.io/)

## Generating OpenAPI document

In order to generate OpenAPI document run Artisan command:

```bash
php artisan openapi:generate
```
