# Operations

Routes are not automatically added to specification.

In order to add route, you need to add `PathItem` attribute to controller class and `Operation` to particular action method.
This attribute will indicate that route which has `UserController@store` definition should be included in `paths`.

```php
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

#[OpenApi\PathItem]
class UserController extends Controller
{
    /**
     * The first comment here will serve as the summary.
     *
     * The second comment will be used as the description.
     * 
     * @deprecated This endpoint is deprecated.
     */
    #[OpenApi\Operation]
    public function store(Request $request)
    {
        //
    }
}
```

The following definition will be generated:

```json
{
    "paths": {
        "\/users": {
            "post": {
                "summary": "Get all users",
                "description": "Get all users from the database.",
                "deprecated": true
            }
        }
    }
}
```

Alternatively, you can achieve the same result by using the Operation attribute directly:

```php
#[OpenApi\Operation(summary: 'Get all users', description: 'Get all users from the database.', deprecated: true)]
```

## Security

See [Security](../security.md#operation-level-example)

## Tags

Tags can be used for logical grouping of operations by resources or any other qualifier.

To use tags, first set them up in `config/openapi.php`:

```php
    'tags' => [

        [
           'name' => 'post',
           'description' => 'Posts',
        ],

        [
           'name' => 'user',
           'description' => 'Application users',

           // You may optionally add a link to external documentation like so:
           'externalDocs' => [
                'description' => 'External API documentation',
                'url' => 'https://example.com/external-docs',
            ],
        ],

    ],
```

Then add them using in the `Operation` attribute on your controller:

```php
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

class UserController extends Controller
{
    /**
     * Create new user.
     *
     * Creates new user or returns already existing user by email.
     */
     #[OpenApi\Operation(tags: ['user'])]
    public function store(Request $request)
    {
        //
    }
}
```

## Resource Controllers and Multiple HTTP Verbs

When using [resource controllers](https://laravel.com/docs/master/controllers#resource-controllers), the `update` method accepts both `PUT` and `PATCH` requests.

When a controller method accepts multiple methods, by default only the first is included in the generated documentation.

To override which verb or method should be used on a particular operation, add the `method` parameter the `Operation` attribute on your controller:

```php
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

class UserController extends Controller
{
    /**
     * Update user.
     *
     * Updates a user.
     *
     */
    #[OpenApi\Operation(tags: ['tags'], method: 'PATCH')]
    public function update(Request $request)
    {
        //
    }
}
```
