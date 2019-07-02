<?php

namespace Vyuldashev\LaravelOpenApi\Builders;

use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Collection;
use Vyuldashev\LaravelOpenApi\Annotations;
use Vyuldashev\LaravelOpenApi\Builders\Paths\OperationsBuilder;
use Vyuldashev\LaravelOpenApi\RouteInformation;

class PathsBuilder
{
    protected $operationsBuilder;

    public function __construct(
        OperationsBuilder $operationsBuilder
    )
    {
        $this->operationsBuilder = $operationsBuilder;
    }

    public function build(): array
    {
        return $this->routes()
            ->groupBy(static function (RouteInformation $routeInformation) {
                return $routeInformation->uri;
            })
            ->map(function (Collection $routes, $uri) {
                $pathItem = PathItem::create()->route($uri);

                $operations = $this->operationsBuilder->build($routes);

                return $pathItem->operations(...$operations);
            })
            ->values()
            ->toArray();
    }

    protected function routes()
    {
        return collect(app(Router::class)->getRoutes())
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
