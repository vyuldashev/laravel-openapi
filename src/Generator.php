<?php

namespace Vyuldashev\LaravelOpenApi;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Components;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Info;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Server;
use GoldSpecDigital\ObjectOrientedOAS\OpenApi;
use Illuminate\Support\Collection;
use Vyuldashev\LaravelOpenApi\Contracts\ParametersNormalizerInterface;
use Vyuldashev\LaravelOpenApi\Contracts\RequestBodyNormalizerInterface;
use Vyuldashev\LaravelOpenApi\Contracts\ResponseNormalizerInterface;

class Generator
{
    public $version = OpenApi::OPENAPI_3_0_2;
    /** @var Info */
    public $info;
    /** @var Server[] */
    public $servers;
    public $schemas = [];
    public $schemasByClass = [];

    public function generate(): OpenApi
    {
        $paths = Routes::resolve()
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
                        /** @var ParametersNormalizerInterface $parametersNormalizer */
                        $parametersNormalizer = resolve($parameters->normalizer);

                        $parameters = $parametersNormalizer->normalize();
                    }

                    /** @var Annotations\RequestBody|null $requestBody */
                    $requestBody = collect($route->actionAnnotations)->first(static function ($annotation) {
                        return $annotation instanceof Annotations\RequestBody;
                    });

                    if ($requestBody) {
                        /** @var RequestBodyNormalizerInterface $requestBodyNormalizer */
                        $requestBodyNormalizer = resolve($requestBody->normalizer);

                        $requestBody = $requestBodyNormalizer->normalize();
                    }

                    $responses = collect($route->actionAnnotations)
                        ->filter(static function ($annotation) {
                            return $annotation instanceof Annotations\Response;
                        })
                        ->map(static function (Annotations\Response $annotation) {
                            return resolve($annotation->normalizer);
                        })
                        ->map(static function (ResponseNormalizerInterface $normalizer) {
                            return $normalizer->normalize();
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

        $openApi = OpenApi::create()
            ->openapi(OpenApi::OPENAPI_3_0_2)
            ->info($this->info)
            ->servers(...$this->servers)
            ->paths(...$paths);

        if (count($this->schemas) > 0) {
            $openApi = $openApi->components(
                Components::create()->schemas(...$this->schemas)
            );
        }

        return $openApi;
    }

    public function setVersion(string $version)
    {
        $this->version = $version;

        return $this;
    }

    public function setInfo(Info $info)
    {
        $this->info = $info;

        return $this;
    }

    public function setServers(array $servers)
    {
        $this->servers = $servers;

        return $this;
    }

    public function setSchemas($schemas)
    {
        $schemas = collect($schemas)
            ->mapWithKeys(static function ($definition, $class) {
                $normalizer = resolve($definition)->normalize();

                return [
                    $class => $normalizer
                ];
            });

        $this->schemas = $schemas->values()->toArray();
        $this->schemasByClass = $schemas->toArray();

        return $this;
    }
}
