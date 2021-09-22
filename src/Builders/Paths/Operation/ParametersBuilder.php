<?php

namespace Vyuldashev\LaravelOpenApi\Builders\Paths\Operation;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\DocBlock\Tags\Param;
use ReflectionParameter;
use Vyuldashev\LaravelOpenApi\Attributes\Parameters;
use Vyuldashev\LaravelOpenApi\Factories\ParametersFactory;
use Vyuldashev\LaravelOpenApi\RouteInformation;
use Vyuldashev\LaravelOpenApi\SchemaHelpers;

class ParametersBuilder
{
    public function build(RouteInformation $route): array
    {
        $pathParameters = $this->buildPath($route);
        $attributedParameters = $this->buildAttribute($route);

        return $pathParameters->merge($attributedParameters)->toArray();
    }

    protected function buildPath(RouteInformation $route): Collection
    {
        return collect($route->parameters)
            ->map(static function (array $parameter) use ($route) {
                $schema = Schema::string();

                /** @var ReflectionParameter|null $reflectionParameter */
                $reflectionParameter = collect($route->actionParameters)
                    ->first(static fn (ReflectionParameter $reflectionParameter) => $reflectionParameter->name === $parameter['name']);

                if ($reflectionParameter) {
                    // The reflected param has no type, so ignore (should be defined in a ParametersFactory instead)
                    if ($reflectionParameter->getType() === null) {
                        return null;
                    }

                    $schema = SchemaHelpers::guessFromReflectionType($reflectionParameter->getType());
                }

                /** @var Param $description */
                $description = collect($route->actionDocBlock->getTagsByName('param'))
                    ->first(static fn (Param $param) => Str::snake($param->getVariableName()) === Str::snake($parameter['name']));

                return Parameter::path()->name($parameter['name'])
                    ->required()
                    ->description(optional(optional($description)->getDescription())->render())
                    ->schema($schema);
            })
            ->filter();
    }

    protected function buildAttribute(RouteInformation $route): Collection
    {
        /** @var Parameters|null $parameters */
        $parameters = $route->actionAttributes->first(static fn ($attribute) => $attribute instanceof Parameters, []);

        if ($parameters) {
            /** @var ParametersFactory $parametersFactory */
            $parametersFactory = app($parameters->factory);

            $parameters = $parametersFactory->build();
        }

        return collect($parameters);
    }
}
