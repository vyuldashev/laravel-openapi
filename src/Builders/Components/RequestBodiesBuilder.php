<?php

namespace Vyuldashev\LaravelOpenApi\Builders\Components;

use Vyuldashev\LaravelOpenApi\Contracts\RequestBodyFactoryInterface;
use Vyuldashev\LaravelOpenApi\Contracts\Reusable;
use Vyuldashev\LaravelOpenApi\Generator;

class RequestBodiesBuilder extends Builder
{
    public function build(string $collection = Generator::COLLECTION_DEFAULT): array
    {
        return $this->getAllClasses($collection)
            ->filter(static function ($class) {
                return
                    is_a($class, RequestBodyFactoryInterface::class, true) &&
                    is_a($class, Reusable::class, true);
            })
            ->map(static function ($class) {
                /** @var RequestBodyFactoryInterface $instance */
                $instance = app($class);

                return $instance->build();
            })
            ->values()
            ->toArray();
    }
}
