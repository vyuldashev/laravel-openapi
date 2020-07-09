# Request Bodies

Generate a request body with this command:

```bash
php artisan openapi:make-requestbody StoreUser
```

Use a request body in your controller like this:

```php
    /**
     * Create a user.
     *
     * @OpenApi\Operation(tags="user")
     * @OpenApi\RequestBody(factory="UserCreateRequestBody")
     */
    public function store(Request $request)
    {
    }
```
