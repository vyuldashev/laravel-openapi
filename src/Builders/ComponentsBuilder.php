<?php

namespace Vyuldashev\LaravelOpenApi\Builders;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Components;
use Vyuldashev\LaravelOpenApi\Builders\Components\CallbacksBuilder;
use Vyuldashev\LaravelOpenApi\Builders\Components\RequestBodiesBuilder;
use Vyuldashev\LaravelOpenApi\Builders\Components\ResponsesBuilder;
use Vyuldashev\LaravelOpenApi\Builders\Components\SchemasBuilder;
use Vyuldashev\LaravelOpenApi\Builders\Components\SecuritySchemesBuilder;
use Vyuldashev\LaravelOpenApi\Contracts\ComponentCreateMiddleware;
use Vyuldashev\LaravelOpenApi\Contracts\ComponentMiddleware;
use Vyuldashev\LaravelOpenApi\Generator;
use Vyuldashev\LaravelOpenApi\Middleware;

class ComponentsBuilder
{
    protected CallbacksBuilder $callbacksBuilder;
    protected RequestBodiesBuilder $requestBodiesBuilder;
    protected ResponsesBuilder $responsesBuilder;
    protected SchemasBuilder $schemasBuilder;
    protected SecuritySchemesBuilder $securitySchemesBuilder;

    public function __construct(
        CallbacksBuilder $callbacksBuilder,
        RequestBodiesBuilder $requestBodiesBuilder,
        ResponsesBuilder $responsesBuilder,
        SchemasBuilder $schemasBuilder,
        SecuritySchemesBuilder $securitySchemesBuilder
    ) {
        $this->callbacksBuilder = $callbacksBuilder;
        $this->requestBodiesBuilder = $requestBodiesBuilder;
        $this->responsesBuilder = $responsesBuilder;
        $this->schemasBuilder = $schemasBuilder;
        $this->securitySchemesBuilder = $securitySchemesBuilder;
    }

    public function build(
        string $collection = Generator::COLLECTION_DEFAULT,
        array $middlewares = []
    ): ?Components {
        $callbacks = $this->callbacksBuilder->build($collection);
        $requestBodies = $this->requestBodiesBuilder->build($collection);
        $responses = $this->responsesBuilder->build($collection);
        $schemas = $this->schemasBuilder->build($collection);
        $securitySchemes = $this->securitySchemesBuilder->build($collection);

        $components = Middleware::make($middlewares)
            ->using(ComponentCreateMiddleware::class)
            ->send(Components::create())
            ->through(fn ($middleware, $components) => $middleware->before($components));

        if (count($callbacks) > 0) {
            $components = $components->callbacks(...$callbacks);
        }

        if (count($requestBodies) > 0) {
            $components = $components->requestBodies(...$requestBodies);
        }

        if (count($responses) > 0) {
            $components = $components->responses(...$responses);
        }

        if (count($schemas) > 0) {
            $components = $components->schemas(...$schemas);
        }

        if (count($securitySchemes) > 0) {
            $components = $components->securitySchemes(...$securitySchemes);
        }

        if (! count($components->toArray())) {
            return null;
        }

        Middleware::make($middlewares)
            ->using(ComponentMiddleware::class)
            ->emit(fn ($middleware) => $middleware->after($components));

        return $components;
    }
}
