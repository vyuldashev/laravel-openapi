<?php

namespace Vyuldashev\LaravelOpenApi\Builders;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Components;
use Vyuldashev\LaravelOpenApi\Builders\Components\ResponsesBuilder;
use Vyuldashev\LaravelOpenApi\Builders\Components\SchemasBuilder;

class ComponentsBuilder
{
    protected $responsesBuilder;
    protected $schemasBuilder;

    public function __construct(
        ResponsesBuilder $responsesBuilder,
        SchemasBuilder $schemasBuilder
    )
    {
        $this->responsesBuilder = $responsesBuilder;
        $this->schemasBuilder = $schemasBuilder;
    }

    public function build(): ?Components
    {
        $responses = $this->responsesBuilder->build();
        $schemas = $this->schemasBuilder->build();

        $components = Components::create();

        if (count($responses) > 0) {
            $components = $components->responses(...$responses);
        }

        if (count($schemas) > 0) {
            $components = $components->schemas(...$schemas);
        }

        $hasAnyObjects = count($responses) > 0 || count($schemas) > 0;

        return $hasAnyObjects ? $components : null;
    }
}
