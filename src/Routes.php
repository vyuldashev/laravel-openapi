<?php

namespace Vyuldashev\LaravelOpenApi;

use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Collection;

class Routes
{
    public static function resolve(): Collection
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
