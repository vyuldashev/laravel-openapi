<?php

namespace Vyuldashev\LaravelOpenApi\Builders\Paths\Operation;

use Vyuldashev\LaravelOpenApi\Annotations\Parameters;
use Vyuldashev\LaravelOpenApi\Factories\ParametersFactory;
use Vyuldashev\LaravelOpenApi\RouteInformation;

class ParametersBuilder
{
    public function build(RouteInformation $route): array
    {
        /** @var Parameters|null $parameters */
        $parameters = collect($route->actionAnnotations)->first(static function ($annotation) {
            return $annotation instanceof Parameters;
        }, []);

        if ($parameters) {
            /** @var ParametersFactory $parametersFactory */
            $parametersFactory = resolve($parameters->factory);

            $parameters = $parametersFactory->build();
        }

        return $parameters;
    }
}
