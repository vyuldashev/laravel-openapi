<?php

namespace Vyuldashev\LaravelOpenApi\Builders\Paths;

use GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Vyuldashev\LaravelOpenApi\Attributes\Operation as OperationAttribute;
use Vyuldashev\LaravelOpenApi\Builders\ExtensionsBuilder;
use Vyuldashev\LaravelOpenApi\Builders\Paths\Operation\CallbacksBuilder;
use Vyuldashev\LaravelOpenApi\Builders\Paths\Operation\ParametersBuilder;
use Vyuldashev\LaravelOpenApi\Builders\Paths\Operation\RequestBodyBuilder;
use Vyuldashev\LaravelOpenApi\Builders\Paths\Operation\ResponsesBuilder;
use Vyuldashev\LaravelOpenApi\Builders\Paths\Operation\SecurityBuilder;
use Vyuldashev\LaravelOpenApi\RouteInformation;

class OperationsBuilder
{
    protected CallbacksBuilder $callbacksBuilder;
    protected ParametersBuilder $parametersBuilder;
    protected RequestBodyBuilder $requestBodyBuilder;
    protected ResponsesBuilder $responsesBuilder;
    protected ExtensionsBuilder $extensionsBuilder;
    protected SecurityBuilder $securityBuilder;

    public function __construct(
        CallbacksBuilder $callbacksBuilder,
        ParametersBuilder $parametersBuilder,
        RequestBodyBuilder $requestBodyBuilder,
        ResponsesBuilder $responsesBuilder,
        ExtensionsBuilder $extensionsBuilder,
        SecurityBuilder $securityBuilder
    ) {
        $this->callbacksBuilder = $callbacksBuilder;
        $this->parametersBuilder = $parametersBuilder;
        $this->requestBodyBuilder = $requestBodyBuilder;
        $this->responsesBuilder = $responsesBuilder;
        $this->extensionsBuilder = $extensionsBuilder;
        $this->securityBuilder = $securityBuilder;
    }

    /**
     * @param RouteInformation[]|Collection $routes
     * @return array
     * @throws InvalidArgumentException
     */
    public function build(array | Collection $routes): array
    {
        $operations = [];

        /** @var RouteInformation[] $routes */
        foreach ($routes as $route) {
            /** @var OperationAttribute|null $operationAttribute */
            $operationAttribute = $route->actionAttributes
                ->first(static fn (object $attribute) => $attribute instanceof OperationAttribute);

            $operationId = optional($operationAttribute)->id;
            $tags = $operationAttribute->tags ?? [];

            $parameters = $this->parametersBuilder->build($route);
            $requestBody = $this->requestBodyBuilder->build($route);
            $responses = $this->responsesBuilder->build($route);
            $callbacks = $this->callbacksBuilder->build($route);
            $security = $this->securityBuilder->build($route);

            $operation = Operation::create()
                ->action(Str::lower($operationAttribute->method) ?: $route->method)
                ->tags(...$tags)
                ->description($route->actionDocBlock->getDescription()->render() !== '' ? $route->actionDocBlock->getDescription()->render() : null)
                ->summary($route->actionDocBlock->getSummary() !== '' ? $route->actionDocBlock->getSummary() : null)
                ->operationId($operationId)
                ->parameters(...$parameters)
                ->requestBody($requestBody)
                ->responses(...$responses)
                ->callbacks(...$callbacks)
                ->security(...$security);

            $this->extensionsBuilder->build($operation, $route->actionAttributes);

            $operations[] = $operation;
        }

        return $operations;
    }
}
