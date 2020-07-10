# Schemas

```bash
php artisan openapi:make-schema User
```

If you would like to generate a schema from model, you may use the `--model` or `-m` option:

```bash
php artisan openapi:make-schema User -m User
```

To use a schema in a response, use and implement `Vyuldashev\LaravelOpenApi\Contracts\Reusable` in your schema, and do something like this in your response:

```php
use App\OpenApi\Schemas\UserSchema;

class ListUsersResponse extends ResponseFactory
{
    public function build(): Response
    {
        return Response::ok()->description('Successful response')->content(
            MediaType::json()->schema(UserSchema::ref())
        );
    }
}
```
