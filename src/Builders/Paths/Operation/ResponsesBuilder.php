<?php

namespace Vyuldashev\LaravelOpenApi\Builders\Paths\Operation;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use Vyuldashev\LaravelOpenApi\Annotations\Response as ResponseAnnotation;
use Vyuldashev\LaravelOpenApi\Contracts\Reusable;
use Vyuldashev\LaravelOpenApi\RouteInformation;

class ResponsesBuilder
{
    public function build(RouteInformation $route): array
    {
        return collect($route->actionAnnotations)
            ->filter(static function ($annotation) {
                return $annotation instanceof ResponseAnnotation;
            })
            ->map(static function (ResponseAnnotation $annotation) {
                $factory = app($annotation->factory);
                $response = $factory->build();

                if ($factory instanceof Reusable) {
                    return Response::ref('#/components/responses/'.$response->objectId)
                        ->statusCode($annotation->statusCode)
                        ->description($annotation->description);
                }

                return $response;
            })
            ->values()
            ->toArray();
    }
}
