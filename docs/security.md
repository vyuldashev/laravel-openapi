# Security

```bash
php artisan openapi:make-security-scheme BearerToken
```

After you generate a security scheme, it will be declared in the `securitySchemes` section, you can apply it to the whole API or individual operations by adding the security section on the root level or operation level, respectively. When used on the root level, security applies the specified security schemes globally to all API operations, unless overridden on the operation level.

## Root level example

`config/openapi.php`:

```php

'security' => [
  GoldSpecDigital\ObjectOrientedOAS\Objects\SecurityRequirement::create()->securityScheme('BearerToken'),
],

```

## Operation level example

```php
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

#[OpenApi\PathItem]
class UserController extends Controller
{
    /**
     * Create new user.
     *
     * Creates new user or returns already existing user by email.
     */
     #[OpenApi\Operation(security: 'BearerTokenSecurityScheme')]
    public function store(Request $request)
    {
        //
    }
}
```
