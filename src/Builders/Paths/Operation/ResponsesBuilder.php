<?php

namespace Vyuldashev\LaravelOpenApi\Builders\Paths\Operation;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use Vyuldashev\LaravelOpenApi\Attributes\Response as ResponseAttribute;
use Vyuldashev\LaravelOpenApi\Contracts\Reusable;
use Vyuldashev\LaravelOpenApi\RouteInformation;

class ResponsesBuilder
{
    public function build(RouteInformation $route): array
    {
        return $route->actionAttributes
            ->filter(static fn (object $attribute) => $attribute instanceof ResponseAttribute)
            ->map(static function (ResponseAttribute $attribute) {
                if (isset($attribute->factories) && is_array($attribute->factories) && $attribute->factory === null) {
                    return collect($attribute->factories)
                        ->map(static function (string $factory) {
                            $factory = app($factory);
                            $response = $factory->build();

                            if ($factory instanceof Reusable) {
                                return Response::ref('#/components/responses/'.$response->objectId);
                            }

                            return $response;
                        })
                        ->values()
                        ->toArray();
                }
                if ($attribute->factory === null) {
                    throw new \InvalidArgumentException('Factory class must be instance of ResponseFactory');
                }
                $factory = app($attribute->factory);
                $response = $factory->build();

                if ($factory instanceof Reusable) {
                    return Response::ref('#/components/responses/'.$response->objectId)
                        ->statusCode($attribute->statusCode)
                        ->description($attribute->description);
                }

                return $response;
            })
            ->values()
            ->flatten()
            ->toArray();
    }
}
