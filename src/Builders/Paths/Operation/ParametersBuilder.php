<?php

namespace Vyuldashev\LaravelOpenApi\Builders\Paths\Operation;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\DocBlock\Tags\Param;
use ReflectionParameter;
use Vyuldashev\LaravelOpenApi\Annotations\Parameters;
use Vyuldashev\LaravelOpenApi\Factories\ParametersFactory;
use Vyuldashev\LaravelOpenApi\RouteInformation;
use Vyuldashev\LaravelOpenApi\SchemaHelpers;

class ParametersBuilder
{
    public function build(RouteInformation $route): array
    {
        $pathParameters = $this->buildPath($route);
        $annotatedParameters = $this->buildAnnotation($route);

        return $pathParameters->merge($annotatedParameters)->toArray();
    }

    protected function buildPath(RouteInformation $route): Collection
    {
        return collect($route->parameters)
            ->map(static function (array $parameter) use ($route) {
                $schema = Schema::string();

                /** @var ReflectionParameter|null $reflectionParameter */
                $reflectionParameter = collect($route->actionParameters)
                    ->first(static function (ReflectionParameter $reflectionParameter) use ($parameter) {
                        return $reflectionParameter->name === $parameter['name'];
                    });

                if ($reflectionParameter) {
                    $schema = SchemaHelpers::guessFromReflectionType($reflectionParameter->getType());
                }

                /** @var Param $description */
                $description = collect($route->actionDocBlock->getTagsByName('param'))
                    ->first(static function (Param $param) use ($parameter) {
                        return Str::snake($param->getVariableName()) === Str::snake($parameter['name']);
                    });

                return Parameter::path()->name($parameter['name'])
                    ->required()
                    ->description(optional(optional($description)->getDescription())->render())
                    ->schema($schema);
            });
    }

    protected function buildAnnotation(RouteInformation $route): Collection
    {
        /** @var Parameters|null $parameters */
        $parameters = collect($route->actionAnnotations)->first(static function ($annotation) {
            return $annotation instanceof Parameters;
        }, []);

        if ($parameters) {
            /** @var ParametersFactory $parametersFactory */
            $parametersFactory = app($parameters->factory);

            $parameters = $parametersFactory->build();
        }

        return collect($parameters);
    }
}
