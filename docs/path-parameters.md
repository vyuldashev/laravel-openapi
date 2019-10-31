# Path Parameters

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
