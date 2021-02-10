# Parameters

In order to add path or query parameters to route you need to create `Parameters` object factory. 

You may generate a new one using Artisan command:

```bash
php artisan openapi:make-parameters ListUsers
```

Here is an example of `Parameters` object factory:

```php
class ListUsersParameters extends ParametersFactory
{
    /**
     * @return Parameter[]
     */
    public function build(): array
    {
        return [

            Parameter::query()
                ->name('withTrashed')
                ->description('Display trashed users too')
                ->required(false)
                ->schema(Schema::boolean()),

        ];
    }
}

```

Finally, add `Parameters` attribute below `Operation` attribute:

```php
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

class UserController extends Controller 
{
    /**
     * List users.
     */
    #[OpenApi\Operation]
    #[OpenApi\Parameters(factory: ListUsersParameters::class)]
    public function index() 
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
            "get": {
                "summary": "List users.",
                "parameters": [
                    {
                        "name": "withTrashed",
                        "in": "query",
                        "description": "Display trashed users too",
                        "required": false,
                        "schema": {
                            "type": "boolean"
                        }
                    }
                ]
            }
        }
    }
}
```

## Route Parameters
 
Let's assume we have route `Route::get('/users/{user}', 'UserController@show')`. 

There is no need to add `Parameters` attribute as route parameters are automatically added to parameters definition:

```php
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

class UserController extends Controller 
{
    /**
     * Show user.
     * 
     * @param User $user User ID
     */
     #[OpenApi\Operation]
    public function show(User $user)
    {
        //
    }
}
```

::: tip
Use @param tag in order to add description to {user} parameter.
:::

The following definition will be generated:

```json
{
    "paths": {
        "\/users\/{user}": {
            "get": {
                "summary": "Show user.",
                "parameters": [
                    {
                        "name": "user",
                        "in": "path",
                        "description": "User ID",
                        "required": true,
                        "schema": {
                            "type": "string"
                        }
                    }
                ]
            }
        }
    }
}
```
