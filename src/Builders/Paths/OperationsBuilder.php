<?php

namespace Vyuldashev\LaravelOpenApi\Builders\Paths;

use GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Vyuldashev\LaravelOpenApi\Annotations\Operation as OperationAnnotation;
use Vyuldashev\LaravelOpenApi\Builders\ExtensionsBuilder;
use Vyuldashev\LaravelOpenApi\Builders\Paths\Operation\CallbacksBuilder;
use Vyuldashev\LaravelOpenApi\Builders\Paths\Operation\ParametersBuilder;
use Vyuldashev\LaravelOpenApi\Builders\Paths\Operation\RequestBodyBuilder;
use Vyuldashev\LaravelOpenApi\Builders\Paths\Operation\ResponsesBuilder;
use Vyuldashev\LaravelOpenApi\RouteInformation;

class OperationsBuilder
{
    protected $callbacksBuilder;
    protected $parametersBuilder;
    protected $requestBodyBuilder;
    protected $responsesBuilder;
    protected $extensionsBuilder;

    public function __construct(
        CallbacksBuilder $callbacksBuilder,
        ParametersBuilder $parametersBuilder,
        RequestBodyBuilder $requestBodyBuilder,
        ResponsesBuilder $responsesBuilder,
        ExtensionsBuilder $extensionsBuilder
    ) {
        $this->callbacksBuilder = $callbacksBuilder;
        $this->parametersBuilder = $parametersBuilder;
        $this->requestBodyBuilder = $requestBodyBuilder;
        $this->responsesBuilder = $responsesBuilder;
        $this->extensionsBuilder = $extensionsBuilder;
    }

    /**
     * @param RouteInformation[]|Collection $routes
     * @return array
     * @throws InvalidArgumentException
     */
    public function build($routes): array
    {
        $operations = [];

        /** @var RouteInformation[] $routes */
        foreach ($routes as $route) {
            $actionAnnotations = collect($route->actionAnnotations);

            /** @var OperationAnnotation $operationAnnotation */
            $operationAnnotation = $actionAnnotations->first(static function ($annotation) {
                return $annotation instanceof OperationAnnotation;
            });

            $operationId = optional($operationAnnotation)->id;
            $tags = $operationAnnotation->tags ?? [];

            $parameters = $this->parametersBuilder->build($route);
            $requestBody = $this->requestBodyBuilder->build($route);
            $responses = $this->responsesBuilder->build($route);
            $callbacks = $this->callbacksBuilder->build($route);

            $operation = Operation::create()
                ->action(Str::lower($operationAnnotation->method) ?: $route->method)
                ->tags(...$tags)
                ->description($route->actionDocBlock->getDescription()->render() !== '' ? $route->actionDocBlock->getDescription()->render() : null)
                ->summary($route->actionDocBlock->getSummary() !== '' ? $route->actionDocBlock->getSummary() : null)
                ->operationId($operationId)
                ->parameters(...$parameters)
                ->requestBody($requestBody)
                ->responses(...$responses)
                ->callbacks(...$callbacks);

            $this->extensionsBuilder->build($operation, $actionAnnotations);

            $operations[] = $operation;
        }

        return $operations;
    }
}
