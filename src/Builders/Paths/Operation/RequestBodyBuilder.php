<?php

namespace Vyuldashev\LaravelOpenApi\Builders\Paths\Operation;

use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use Vyuldashev\LaravelOpenApi\Annotations\RequestBody as RequestBodyAnnotation;
use Vyuldashev\LaravelOpenApi\Factories\RequestBodyFactory;
use Vyuldashev\LaravelOpenApi\RouteInformation;

class RequestBodyBuilder
{
    public function build(RouteInformation $route): ?RequestBody
    {
        /** @var RequestBodyAnnotation|null $requestBody */
        $requestBody = collect($route->actionAnnotations)->first(static function ($annotation) {
            return $annotation instanceof RequestBodyAnnotation;
        });

        if ($requestBody) {
            /** @var RequestBodyFactory $requestBodyFactory */
            $requestBodyFactory = resolve($requestBody->factory);

            $requestBody = $requestBodyFactory->build();
        }

        return $requestBody;
    }
}
