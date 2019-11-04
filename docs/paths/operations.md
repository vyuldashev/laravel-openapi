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
     * @Operation()
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

