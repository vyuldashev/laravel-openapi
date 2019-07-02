<?php

namespace Vyuldashev\LaravelOpenApi\Builders;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Collection;
use Vyuldashev\LaravelOpenApi\Annotations;
use Vyuldashev\LaravelOpenApi\Contracts\Reusable;
use Vyuldashev\LaravelOpenApi\Factories\ParametersFactory;
use Vyuldashev\LaravelOpenApi\Factories\RequestBodyFactory;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;
use Vyuldashev\LaravelOpenApi\RouteInformation;

class PathsBuilder
{
    public function build(): array
    {
        return $this->routes()
            ->groupBy(static function (RouteInformation $routeInformation) {
                return $routeInformation->uri;
            })
            ->map(static function (Collection $routes, $uri) {
                $pathItem = PathItem::create()->route($uri);

                $operations = [];

                /** @var RouteInformation[] $routes */
                foreach ($routes as $route) {
                    // Operation ID
                    $operationId = collect($route->actionAnnotations)->first(static function ($annotation) {
                        return $annotation instanceof Annotations\Operation;
                    });

                    // Parameters

                    /** @var Annotations\Parameters|null $parameters */
                    $parameters = collect($route->actionAnnotations)->first(static function ($annotation) {
                        return $annotation instanceof Annotations\Parameters;
                    }, []);

                    if ($parameters) {
                        /** @var ParametersFactory $parametersFactory */
                        $parametersFactory = resolve($parameters->factory);

                        $parameters = $parametersFactory->build();
                    }

                    /** @var Annotations\RequestBody|null $requestBody */
                    $requestBody = collect($route->actionAnnotations)->first(static function ($annotation) {
                        return $annotation instanceof Annotations\RequestBody;
                    });

                    if ($requestBody) {
                        /** @var RequestBodyFactory $requestBodyFactory */
                        $requestBodyFactory = resolve($requestBody->factory);

                        $requestBody = $requestBodyFactory->build();
                    }

                    $responses = collect($route->actionAnnotations)
                        ->filter(static function ($annotation) {
                            return $annotation instanceof Annotations\Response;
                        })
                        ->map(static function (Annotations\Response $annotation) {
                            return resolve($annotation->factory);
                        })
                        ->map(static function (ResponseFactory $factory) {
                            // TODO
                            $response = $factory->build();

                            if ($factory instanceof Reusable) {
                                return Response::ref('#/components/responses/' . $response->objectId);
                            }

                            return $response;
                        })
                        ->values()
                        ->toArray();

                    $operation = Operation::create()
                        ->action($route->method)
                        ->description($route->actionDocBlock->getDescription()->render() !== '' ? $route->actionDocBlock->getDescription()->render() : null)
                        ->summary($route->actionDocBlock->getSummary() !== '' ? $route->actionDocBlock->getSummary() : null)
                        ->operationId(optional($operationId)->id)
                        ->parameters(...$parameters)
                        ->requestBody($requestBody)
                        ->responses(...$responses);

                    $operations[] = $operation;
                }

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
