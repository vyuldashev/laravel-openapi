# Collections

Collections permit the declaration of multiple OpenAPI document 'collection' configurations.

The openapi.php config file contains a single collection configuration by default, titled 'default'.

Additional collection configurations may be added to the collections array, the key of the entry represents the name of the collection.

Where Schemas should belong to specific collections, the 'Collection' annotation can be added to the class definition, with a name value matching the collection name e.g.

```php
namespace App\OpenApi\V1\Schemas;

use Vyuldashev\LaravelOpenApi\Factories\SchemaFactory;
use Vyuldashev\LaravelOpenApi\Contracts\Reusable;
use Vyuldashev\LaravelOpenApi\Annotations as OpenApi;

/**
 * @OpenApi\Collection(name = "v1")
 **/
class QuoteOfferSchema extends SchemaFactory implements Reusable
{
    ...
}
```

Controller methods can also be assigned to a collection using the 'Collection' annotation.

```php
namespace App\Api\V1\Controllers;

use Vyuldashev\LaravelOpenApi\Annotations as OpenApi;

/**
 * @OpenApi\Collection(name = "v1")
 **/
class DemoController extends Controller
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @OpenApi\Collection(name="v1")
     * @OpenApi\Operation(tags="demo")
     * @OpenApi\Response(factory="App\OpenApi\V1\Responses\DemoResponse", statusCode=200)
     */
    public function create(Request $request): JsonResponse
    {
        ...
    }
}
```

## Web

To permit web UI routing to resolve specific collection documentation, it is necessary to override this package's provided OpenApiController and provide the generator's generate method with the collection name.

One way of doing this is by using named route parameters, setting the opeanapi.php config collection's routes like so:

```php
  'route' => [
      'uri' => '/openapi/{collection}',
      'middleware' => [...],
  ],
```

Custom controller:

```php
<?php

namespace App\OpenApi;

use GoldSpecDigital\ObjectOrientedOAS\OpenApi;
use Vyuldashev\LaravelOpenApi\Generator;

class OpenApiController
{
    public function show(Generator $generator, string $collection): OpenApi
    {
        return $generator->generate($collection);
    }
}
```

You will then need to bind this controller in the register method of a service provider like so:

```php
<?php

namespace App\Providers;

use App\OpenApi\OpenApiController as CustomOpenApiController;
use Illuminate\Support\ServiceProvider;
use Vyuldashev\LaravelOpenApi\Http\OpenApiController;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(OpenApiController::class, function ($app) {
            return $app->make(CustomOpenApiController::class);
        });
    }
```

## CLI

The openapi:generate command takes an optional collection parameter, which is 'default' by default:

The below example will generate the OpenAPI spec for a collection named 'v1', if it exists in the openapi.php config file's collections array:

```
php artisan openapi:generate v1
```

