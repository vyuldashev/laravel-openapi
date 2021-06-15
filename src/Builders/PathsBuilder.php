<?php

namespace Vyuldashev\LaravelOpenApi\Builders;

use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Vyuldashev\LaravelOpenApi\Attributes;
use Vyuldashev\LaravelOpenApi\Attributes\Collection as CollectionAttribute;
use Vyuldashev\LaravelOpenApi\Builders\Paths\OperationsBuilder;
use Vyuldashev\LaravelOpenApi\Contracts\PathMiddleware;
use Vyuldashev\LaravelOpenApi\Generator;
use Vyuldashev\LaravelOpenApi\RouteInformation;

class PathsBuilder
{
    protected Collection $routes;

    public function __construct(
        protected OperationsBuilder $operationsBuilder
    ) {}

    /**
     * @param string $collection
     * @param PathMiddleware[] $middlewares
     * @return array
     */
    public function build(
        string $collection,
        array $middlewares
    ): array {
        return $this->routes()
            ->filter(static function (RouteInformation $routeInformation) use ($collection) {
                /** @var CollectionAttribute|null $collectionAttribute */
                $collectionAttribute = collect()
                    ->merge($routeInformation->controllerAttributes)
                    ->merge($routeInformation->actionAttributes)
                    ->first(static fn(object $item) => $item instanceof CollectionAttribute);

                return
                    (! $collectionAttribute && $collection === Generator::COLLECTION_DEFAULT) ||
                    ($collectionAttribute && in_array($collection, $collectionAttribute->name, true));
            })
            ->map(static function (RouteInformation $item) use ($middlewares) {
                foreach ($middlewares as $middleware) {
                    app($middleware)->before($item);
                }

                return $item;
            })
            ->groupBy(static fn(RouteInformation $routeInformation) => $routeInformation->uri)
            ->map(function (Collection $routes, $uri) {
                $pathItem = PathItem::create()->route($uri);

                $operations = $this->operationsBuilder->build($routes);

                return $pathItem->operations(...$operations);
            })
            ->map(static function (PathItem $item) use ($middlewares) {
                foreach ($middlewares as $middleware) {
                    $item = app($middleware)->after($item);
                }

                return $item;
            })
            ->values()
            ->toArray();
    }

    protected function routes(): Collection
    {
        /** @noinspection CollectFunctionInCollectionInspection */
        $this->routes = collect(app(Router::class)->getRoutes())
            ->filter(static fn(Route $route) => $route->getActionName() !== 'Closure')
            ->map(static fn(Route $route) => RouteInformation::createFromRoute($route))
            ->filter(static function (RouteInformation $route) {
                $pathItem = $route->controllerAttributes
                    ->first(static fn(object $attribute) => $attribute instanceof Attributes\PathItem);

                $operation = $route->actionAttributes
                    ->first(static fn(object $attribute) => $attribute instanceof Attributes\Operation);

                return $pathItem && $operation;
            });

        if (config('openapi.clone_routes_with_optional_params', false)) {
            $this->cloneRoutesWithOptionalParameters();
            dd($this->routes);
        }

        return $this->routes;
    }

    /**
     * @todo Add support for routes with multiple optional parameters, currently only supports one optional parameter
     */
    protected function cloneRoutesWithOptionalParameters(): void
    {
        $routes = $this->routes;
        $routes->filter(
            fn(RouteInformation $route) => $route->parameters->where('required', false)->isNotEmpty()
        )->each(function(RouteInformation $route) {
            $route = clone $route;
            $uri = Str::of($route->uri);
            if ($uri->substrCount('?') === 1) {
                $route->uri = $uri->replaceMatches('/\{(.*?)\}/', '')->rtrim('/');
                $route->parameters = collect();
                $route->actionParameters = [];
                $this->routes->prepend($route);
            }
        });
    }
}
