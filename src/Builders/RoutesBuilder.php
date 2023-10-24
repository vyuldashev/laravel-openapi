<?php

namespace Vyuldashev\LaravelOpenApi\Builders;

use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Vyuldashev\LaravelOpenApi\Attributes;
use Vyuldashev\LaravelOpenApi\Contracts\RouteInformationMiddleware;
use Vyuldashev\LaravelOpenApi\RouteInformation;

class RoutesBuilder
{
    /**
     * @var Router
     */
    protected Router $router;

    /**
     * @param  Router  $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @param  RouteInformationMiddleware[]  $middlewares
     * @return array
     */
    public function build(array $middlewares): array
    {
        /** @noinspection CollectFunctionInCollectionInspection */
        return collect($this->router->getRoutes())
            ->filter(static fn (Route $route) => $route->getActionName() !== 'Closure')
            ->map(static fn (Route $route) => RouteInformation::createFromRoute($route))
            ->map(static function (RouteInformation $route) use ($middlewares): RouteInformation {
                foreach ($middlewares as $middleware) {
                    $route = app($middleware)->after($route);
                }

                return $route;
            })
            ->filter(static function (RouteInformation $route): bool {
                $pathItem = $route->controllerAttributes
                    ->first(static fn (object $attribute) => $attribute instanceof Attributes\PathItem);

                $operation = $route->actionAttributes
                    ->first(static fn (object $attribute) => $attribute instanceof Attributes\Operation);

                return $pathItem && $operation;
            })
            ->toArray();
    }
}
