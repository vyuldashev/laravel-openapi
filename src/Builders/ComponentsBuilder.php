<?php

namespace Vyuldashev\LaravelOpenApi\Builders;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Components;
use Vyuldashev\LaravelOpenApi\Builders\Components\CallbacksBuilder;
use Vyuldashev\LaravelOpenApi\Builders\Components\RequestBodiesBuilder;
use Vyuldashev\LaravelOpenApi\Builders\Components\ResponsesBuilder;
use Vyuldashev\LaravelOpenApi\Builders\Components\SchemasBuilder;
use Vyuldashev\LaravelOpenApi\Builders\Components\SecuritySchemesBuilder;

class ComponentsBuilder
{
    protected $callbacksBuilder;
    protected $requestBodiesBuilder;
    protected $responsesBuilder;
    protected $schemasBuilder;
    protected $securitySchemesBuilder;

    public function __construct(
        CallbacksBuilder $callbacksBuilder,
        RequestBodiesBuilder $requestBodiesBuilder,
        ResponsesBuilder $responsesBuilder,
        SchemasBuilder $schemasBuilder,
        SecuritySchemesBuilder $securitySchemesBuilder
    )
    {
        $this->callbacksBuilder = $callbacksBuilder;
        $this->requestBodiesBuilder = $requestBodiesBuilder;
        $this->responsesBuilder = $responsesBuilder;
        $this->schemasBuilder = $schemasBuilder;
        $this->securitySchemesBuilder = $securitySchemesBuilder;
    }

    public function build(): ?Components
    {
        $callbacks = $this->callbacksBuilder->build();
        $requestBodies = $this->requestBodiesBuilder->build();
        $responses = $this->responsesBuilder->build();
        $schemas = $this->schemasBuilder->build();
        $securitySchemes = $this->securitySchemesBuilder->build();

        $components = Components::create();

        $hasAnyObjects = false;

        if (count($callbacks) > 0) {
            $hasAnyObjects = true;

            $components = $components->callbacks(...$callbacks);
        }

        if (count($requestBodies) > 0) {
            $hasAnyObjects = true;

            $components = $components->requestBodies(...$requestBodies);
        }

        if (count($responses) > 0) {
            $hasAnyObjects = true;
            $components = $components->responses(...$responses);
        }

        if (count($schemas) > 0) {
            $hasAnyObjects = true;
            $components = $components->schemas(...$schemas);
        }

        if (count($securitySchemes) > 0) {
            $hasAnyObjects = true;
            $components = $components->securitySchemes(...$securitySchemes);
        }

        return $hasAnyObjects ? $components : null;
    }
}
