<?php

namespace Vyuldashev\LaravelOpenApi\Builders;

use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;
use Illuminate\Support\Collection;
use Vyuldashev\LaravelOpenApi\Attributes\Collection as CollectionAttribute;
use Vyuldashev\LaravelOpenApi\Builders\Paths\OperationsBuilder;
use Vyuldashev\LaravelOpenApi\Contracts\PathMiddleware;
use Vyuldashev\LaravelOpenApi\Generator;
use Vyuldashev\LaravelOpenApi\Middleware;
use Vyuldashev\LaravelOpenApi\RouteInformation;

class PathsBuilder
{
    protected OperationsBuilder $operationsBuilder;

    public function __construct(
        OperationsBuilder $operationsBuilder
    ) {
        $this->operationsBuilder = $operationsBuilder;
    }

    /**
     * @param  string  $collection
     * @param  PathMiddleware[]  $middlewares
     * @return array
     */
    public function build(
        string $collection,
        array $middlewares
    ): array {
        return $this->routes($middlewares)
            ->filter(static function (RouteInformation $routeInformation) use ($collection) {
                /** @var CollectionAttribute|null $collectionAttribute */
                $collectionAttribute = collect()
                    ->merge($routeInformation->controllerAttributes)
                    ->merge($routeInformation->actionAttributes)
                    ->first(static fn (object $item) => $item instanceof CollectionAttribute);

                return
                    (! $collectionAttribute && $collection === Generator::COLLECTION_DEFAULT) ||
                    ($collectionAttribute && in_array($collection, $collectionAttribute->name, true));
            })
            ->map(static function (RouteInformation $routeInformation) use ($middlewares) {
                Middleware::make($middlewares)
                    ->using(PathMiddleware::class)
                    ->emit(fn ($middleware) => $middleware->before($routeInformation));
                return $routeInformation;
            })
            ->groupBy(static fn (RouteInformation $routeInformation) => $routeInformation->uri)
            ->map(function (Collection $routes, $uri) {
                $pathItem = PathItem::create()->route($uri);

                $operations = $this->operationsBuilder->build($routes);

                return $pathItem->operations(...$operations);
            })
            ->map(static function (PathItem $item) use ($middlewares) {
                return Middleware::make($middlewares)
                    ->using(PathMiddleware::class)
                    ->send($item)
                    ->through(fn ($middleware, $item) => $middleware->after($item));
            })
            ->values()
            ->toArray();
    }

    protected function routes(array $middlewares): Collection
    {
        return collect(app(RoutesBuilder::class)->build($middlewares));
    }
}
