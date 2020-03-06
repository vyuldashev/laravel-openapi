<?php

namespace Vyuldashev\LaravelOpenApi\Builders\Paths\Operation;

use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;
use Vyuldashev\LaravelOpenApi\Annotations\Callback as CallbackAnnotation;
use Vyuldashev\LaravelOpenApi\Contracts\Reusable;
use Vyuldashev\LaravelOpenApi\RouteInformation;

class CallbacksBuilder
{
    public function build(RouteInformation $route): array
    {
        return collect($route->actionAnnotations)
            ->filter(static function ($annotation) {
                return $annotation instanceof CallbackAnnotation;
            })
            ->map(static function (CallbackAnnotation $annotation) {
                $factory = app($annotation->factory);
                $pathItem = $factory->build();

                if ($factory instanceof Reusable) {
                    return PathItem::ref('#/components/callbacks/'.$pathItem->objectId);
                }

                return $pathItem;
            })
            ->values()
            ->toArray();
    }
}
