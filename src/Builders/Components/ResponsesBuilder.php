<?php

namespace Vyuldashev\LaravelOpenApi\Builders\Components;

use Roave\BetterReflection\Reflection\ReflectionClass;
use Vyuldashev\LaravelOpenApi\Contracts\Reusable;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;
use Vyuldashev\LaravelOpenApi\Generator;

class ResponsesBuilder extends Builder
{
    public function build(string $collection = Generator::COLLECTION_DEFAULT): array
    {
        return $this->getAllClasses()
            ->filter($this->filterForCollection($collection))
            ->map(static function (ReflectionClass $reflectionClass) {
                return $reflectionClass->getName();
            })
            ->filter(static function ($class) {
                return
                    is_a($class, ResponseFactory::class, true) &&
                    is_a($class, Reusable::class, true);
            })
            ->map(static function ($class) {
                /** @var ResponseFactory $instance */
                $instance = resolve($class);

                return $instance->build();
            })
            ->values()
            ->toArray();
    }
}
