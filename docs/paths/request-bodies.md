# Request Bodies

Generate a request body with this command:

```bash
php artisan openapi:make-requestbody StoreUser
```

You can refer to a schema by implementing `use Vyuldashev\LaravelOpenApi\Contracts\Reusable` on the schema and adding it to the request body like so:

```php
class UserCreateRequestBody extends RequestBodyFactory
{
    public function build(): RequestBody
    {
        return RequestBody::create('UserCreate')
            ->description('User data')
            ->content(
                MediaType::json()->schema(UserSchema::ref())
            );
    }
}
```

Use a request body in your controller like this:

```php
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

class UserController extends Controller
{
    /**
     * Create a user.
     */
    #[OpenApi\Operation(tags: ['user'])]
    #[OpenApi\RequestBody(factory: UserCreateRequestBody::class)]
    public function store(Request $request)
    {
    }
}
```
