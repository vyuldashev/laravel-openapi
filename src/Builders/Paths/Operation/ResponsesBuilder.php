<?php

namespace Vyuldashev\LaravelOpenApi\Builders\Paths\Operation;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use Vyuldashev\LaravelOpenApi\Annotations\Response as ResponseAnnotation;
use Vyuldashev\LaravelOpenApi\Contracts\Reusable;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;
use Vyuldashev\LaravelOpenApi\RouteInformation;

class ResponsesBuilder
{
    public function build(RouteInformation $route): array
    {
        return collect($route->actionAnnotations)
            ->filter(static function ($annotation) {
                return $annotation instanceof ResponseAnnotation;
            })
            ->map(function (ResponseAnnotation $annotation) {
                if ($annotation->factory) {
                    return $this->buildUsingFactory($annotation);
                }

                return $this->buildUsingRef($annotation);
            })
            ->values()
            ->toArray();
    }

    protected function buildUsingFactory(ResponseAnnotation $annotation): Response
    {
        /** @var ResponseFactory $factory */
        $factory = resolve($annotation->factory);

        $response = $factory->build();

        if ($factory instanceof Reusable) {
            return Response::ref('#/components/responses/' . $response->objectId);
        }

        return $response;
    }

    protected function buildUsingRef(ResponseAnnotation $annotation): Response
    {
        return Response::ref($annotation->ref)->statusCode($annotation->statusCode);
    }
}
