<?php

namespace Vyuldashev\LaravelOpenApi;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;
use Illuminate\Support\Collection;
use Vyuldashev\LaravelOpenApi\Contracts\ParametersNormalizerInterface;
use Vyuldashev\LaravelOpenApi\Contracts\RequestBodyNormalizerInterface;
use Vyuldashev\LaravelOpenApi\Contracts\ResponseNormalizerInterface;

class Container
{
    /** @var RouteInformation[] */
    public static $routes = [];

    public static $schemas = [];

    public static $schemasByClass = [];

    public static $paths = [];

    /**
     * @param RouteInformation[] $routes
     */
    public static function routes(array $routes): void
    {
        static::$routes = $routes;

        static::$paths = collect($routes)
            ->groupBy(static function (RouteInformation $routeInformation) {
                return $routeInformation->uri;
            })
            ->map(static function (Collection $routes, $uri) {
                $pathItem = PathItem::create()->route($uri);

                $operations = [];

                /** @var RouteInformation[] $routes */
                foreach ($routes as $route) {
                    // Operation ID
                    $operationId = collect($route->actionAnnotations)->first(function ($annotation) {
                        return $annotation instanceof Annotations\Operation;
                    });

                    // Parameters

                    /** @var Annotations\Parameters|null $parameters */
                    $parameters = collect($route->actionAnnotations)->first(function ($annotation) {
                        return $annotation instanceof Annotations\Parameters;
                    }, []);

                    if ($parameters) {
                        /** @var ParametersNormalizerInterface $parametersNormalizer */
                        $parametersNormalizer = resolve($parameters->normalizer);

                        $parameters = $parametersNormalizer->normalize();
                    }

                    /** @var Annotations\RequestBody|null $requestBody */
                    $requestBody = collect($route->actionAnnotations)->first(function ($annotation) {
                        return $annotation instanceof Annotations\RequestBody;
                    });

                    if ($requestBody) {
                        /** @var RequestBodyNormalizerInterface $requestBodyNormalizer */
                        $requestBodyNormalizer = resolve($requestBody->normalizer);

                        $requestBody = $requestBodyNormalizer->normalize();
                    }

                    $responses = collect($route->actionAnnotations)
                        ->filter(function ($annotation) {
                            return $annotation instanceof Annotations\Response;
                        })
                        ->map(function (Annotations\Response $annotation) {
                            return resolve($annotation->normalizer);
                        })
                        ->map(function (ResponseNormalizerInterface $normalizer) {
                            return $normalizer->normalize();
                        })
                        ->values()
                        ->toArray();

                    $operation = Operation::create()
                        ->action($route->method)
                        ->description($route->actionDocBlock->getDescription())
                        ->summary($route->actionDocBlock->getSummary())
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

    public static function schemas($schemas): void
    {
        $schemas = collect($schemas)
            ->mapWithKeys(static function ($definition, $class) {
                $normalizer = resolve($definition)->normalize();

                return [
                    $class => $normalizer
                ];
            });

        static::$schemas = $schemas->values()->toArray();
        static::$schemasByClass = $schemas->toArray();

    }
}
