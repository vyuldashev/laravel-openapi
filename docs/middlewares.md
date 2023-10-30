# Middlewares

Middlewares are an optional bit of logic to transform the given data at various lifecycle points.

## Route generation
### Routes

To add a route middleware create a class that implements `\Vyuldashev\LaravelOpenApi\Contracts\RoutesBuilderMiddleware` then register it by referencing it in the  `openapi.collections.default.middlewares.paths` config array like `MyRouteMiddleware::class`

Available lifecycle points are:
- `after` - after the `RouteInformation` has been built.

### Paths

To add a path middleware create a class that implements `\Vyuldashev\LaravelOpenApi\Contracts\PathMiddleware` then register it by referencing it in the  `openapi.collections.default.middlewares.paths` config array like `MyPathMiddleware::class`

Available lifecycle points are:
 - `before` - after collecting all `RouteInformation` but before processing them.
 - `after` - after the `PathItem` has been built.

## Component generation
### Pre-component generation

To add a path middleware create a class that implements `\Vyuldashev\LaravelOpenApi\Contracts\ComponentCreateMiddleware` then register it by referencing it in the  `openapi.collections.default.middlewares.components` config array like `MyComponentMiddleware::class`

Available lifecycle points are:
- `before` - before the `Components` has been built.

### Post-component generation

To add a path middleware create a class that implements `\Vyuldashev\LaravelOpenApi\Contracts\ComponentMiddleware` then register it by referencing it in the  `openapi.collections.default.middlewares.components` config array like `MyComponentMiddleware::class`

Available lifecycle points are:
- `after` - after the `Components` has been built.
