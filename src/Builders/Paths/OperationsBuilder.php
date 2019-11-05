<?php

namespace Vyuldashev\LaravelOpenApi\Builders\Paths;

use GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use Illuminate\Support\Collection;
use Vyuldashev\LaravelOpenApi\Annotations\Operation as OperationAnnotation;
use Vyuldashev\LaravelOpenApi\Builders\Paths\Operation\ParametersBuilder;
use Vyuldashev\LaravelOpenApi\Builders\Paths\Operation\RequestBodyBuilder;
use Vyuldashev\LaravelOpenApi\Builders\Paths\Operation\ResponsesBuilder;
use Vyuldashev\LaravelOpenApi\RouteInformation;

class OperationsBuilder
{
    protected $parametersBuilder;
    protected $requestBodyBuilder;
    protected $responsesBuilder;

    public function __construct(
        ParametersBuilder $parametersBuilder,
        RequestBodyBuilder $requestBodyBuilder,
        ResponsesBuilder $responsesBuilder
    )
    {
        $this->parametersBuilder = $parametersBuilder;
        $this->requestBodyBuilder = $requestBodyBuilder;
        $this->responsesBuilder = $responsesBuilder;
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
            /** @var OperationAnnotation $operationAnnotation */
            $operationAnnotation = collect($route->actionAnnotations)->first(static function ($annotation) {
                return $annotation instanceof OperationAnnotation;
            });

            $operationId = optional($operationAnnotation)->id;
            $tags = [];

            if ($operationAnnotation->tags !== null) {
                $tags = collect(explode(',', $operationAnnotation->tags))->filter()->map(static function (string $value) {
                    return trim($value);
                });
            }

            $parameters = $this->parametersBuilder->build($route);
            $requestBody = $this->requestBodyBuilder->build($route);
            $responses = $this->responsesBuilder->build($route);

            $operation = Operation::create()
                ->action($route->method)
                ->tags(...$tags)
                ->description($route->actionDocBlock->getDescription()->render() !== '' ? $route->actionDocBlock->getDescription()->render() : null)
                ->summary($route->actionDocBlock->getSummary() !== '' ? $route->actionDocBlock->getSummary() : null)
                ->operationId($operationId)
                ->parameters(...$parameters)
                ->requestBody($requestBody)
                ->responses(...$responses);

            $operations[] = $operation;
        }

        return $operations;
    }
}
