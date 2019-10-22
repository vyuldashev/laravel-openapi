# Generate OpenAPI specification for Laravel Applications

[![Latest Version on Packagist](https://poser.pugx.org/vyuldashev/laravel-openapi/v/stable?format=flat-square)](https://packagist.org/packages/vyuldashev/laravel-openapi)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/vyuldashev/laravel-openapi/master.svg?style=flat-square)](https://travis-ci.org/vyuldashev/laravel-openapi)
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
    Vyuldashev\LaravelOpenApi\OpenApiServiceProvider::class,
];
```

You can publish the config file with:

```bash
php artisan vendor:publish --provider="Vyuldashev\LaravelOpenApi\OpenApiServiceProvider" --tag="openapi-config"
```

## Usage

*This package use some annotations. If you are using PhpStorm consider installing [PHP Annotations](https://plugins.jetbrains.com/plugin/7320-php-annotations/) plugin.*

Before starting using this package you need to be familiar with OpenAPI specification and it's terms.

Here are some useful links that will help to gain enough knowledge:
* [OpenAPI Specification](https://github.com/OAI/OpenAPI-Specification/blob/master/versions/3.0.2.md)
* [OpenAPI Map](https://openapi-map.apihandyman.io)
* [Swagger Editor](https://editor.swagger.io/)

### Generating speficiation

In order to generate OpenAPI specification use Artisan command:

```bash
php artisan openapi:generate
```

### Adding route to paths

Routes are not automatically added to specification. 

In order to add route, you need to add `PathItem` annotation to controller class and `Operation` to particular action method. This annotations will indicate that route which has `UserController@store` definition should be included in `paths`.

```php
use Vyuldashev\LaravelOpenApi\Annotations as OpenApi;

/**
 * @OpenApi\PathItem()
 */
class UserController extends Controller 
{
    /**
     * Create new user.
     * 
     * @OpenApi\Operation()
    */
    public function store() 
    {
        //
    }
}
```

### Factories

#### Route Parameters

In order to add path or query parameters to route you need to create `Parameters` object factory. 

You may generate a new one using Artisan command:

```bash
php artisan openapi:make-parameters GetUser
```

Here is an example of `Parameters` object factory:

```php
class GetUserParameters extends ParametersFactory
{
    /**
     * @return Parameter[]
     */
    public function build(): array
    {
        return [

            Parameter::path()
                ->name('user')
                ->description('User ID')
                ->required()
                ->schema(Schema::integer()),

        ];
    }
}
```

Finally, add `Parameters` annotation below `Operation` annotation:

```php
class UserController extends Controller 
{
    /**
     * Get user.
     * 
     * @OpenApi\Operation()
     * @OpenApi\Parameters(factory="GetUserParameters")
    */
    public function show(User $user) 
    {
        //
    }
}
```

#### Request Bodies

```bash
php artisan openapi:make-requestbody StoreUser
```

#### Responses

```bash
php artisan openapi:make-response ListUsers
```

#### Schemas

```bash
php artisan openapi:make-schema User
```

If you would like to generate a schema from model, you may use the `--model` or `-m` option:

```bash
php artisan openapi:make-schema User -m User
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
