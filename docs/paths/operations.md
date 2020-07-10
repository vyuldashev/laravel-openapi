# Operations

Routes are not automatically added to specification.

In order to add route, you need to add `PathItem` annotation to controller class and `Operation` to particular action method.
This annotations will indicate that route which has `UserController@store` definition should be included in `paths`.

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
     * Creates new user or returns already existing user by email.
     *
     * @OpenApi\Operation()
     */
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
                "summary": "Create new user.",
                "description": "Creates new user or returns already existing user by email."
            }
        }
    }
}
```

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
        ],

    ],
```

Then add them using in the `Operation` annotation on your controller:

```php
class UserController extends Controller
{
    /**
     * Create new user.
     *
     * Creates new user or returns already existing user by email.
     *
     * @Operation(tags="user")
     */
    public function store(Request $request)
    {
        //
    }
}
```
