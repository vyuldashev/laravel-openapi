# Middlewares

Middlewares are an optional bit of logic to transform the given data at various lifecycle points.

### Path

To add a path middleware create a class that implements `\Vyuldashev\LaravelOpenApi\Contracts\PathMiddleware` then register it by referencing it in the  `openapi.collections.default.middlewares.paths` config array like `MyPathMiddleware::class`

Available lifecycle points are:
 - `before` - after collecting all `RouteInformation` but before processing them.
 - `after` - after the `PathItem` has been built.
