<?php

namespace Vyuldashev\LaravelOpenApi\Builders;

use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Collection;
use Vyuldashev\LaravelOpenApi\Annotations;
use Vyuldashev\LaravelOpenApi\Annotations\Collection as CollectionAnnotation;
use Vyuldashev\LaravelOpenApi\Builders\Paths\OperationsBuilder;
use Vyuldashev\LaravelOpenApi\Contracts\PathMiddleware;
use Vyuldashev\LaravelOpenApi\Generator;
use Vyuldashev\LaravelOpenApi\RouteInformation;

class PathsBuilder
{
    protected $operationsBuilder;

    public function __construct(
        OperationsBuilder $operationsBuilder
    ) {
        $this->operationsBuilder = $operationsBuilder;
    }

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
                /** @var CollectionAnnotation|null $collectionAnnotation */
                $collectionAnnotation = collect()
                    ->merge($routeInformation->controllerAnnotations)
                    ->merge($routeInformation->actionAnnotations)
                    ->first(static function ($item) {
                        return $item instanceof CollectionAnnotation;
                    });

                return
                    (!$collectionAnnotation && $collection === Generator::COLLECTION_DEFAULT) ||
                    ($collectionAnnotation && in_array($collection, $collectionAnnotation->name, true));
            })
            ->map(static function (RouteInformation $item) use ($middlewares) {
                foreach ($middlewares as $middleware) {
                    app($middleware)->before($item);
                }

                return $item;
            })
            ->groupBy(static function (RouteInformation $routeInformation) {
                return $routeInformation->uri;
            })
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
        return collect(app(Router::class)->getRoutes())
            ->filter(static function (Route $route) {
                return $route->getActionName() !== 'Closure';
            })
            ->map(static function (Route $route) {
                return RouteInformation::createFromRoute($route);
            })
            ->filter(static function (RouteInformation $route) {
                $pathItem = collect($route->controllerAnnotations)->first(static function ($annotation) {
                    return $annotation instanceof Annotations\PathItem;
                });

                $operation = collect($route->actionAnnotations)->first(static function ($annotation) {
                    return $annotation instanceof Annotations\Operation;
                });

                return $pathItem && $operation;
            });
    }
}
