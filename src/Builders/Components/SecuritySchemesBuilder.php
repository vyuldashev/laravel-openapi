<?php

namespace Vyuldashev\LaravelOpenApi\Builders\Components;

use Roave\BetterReflection\Reflection\ReflectionClass;
use Vyuldashev\LaravelOpenApi\Factories\SecuritySchemeFactory;
use Vyuldashev\LaravelOpenApi\Generator;

class SecuritySchemesBuilder extends Builder
{
    public function build(string $collection = Generator::COLLECTION_DEFAULT): array
    {
        return $this->getAllClasses()
            ->filter($this->filterForCollection($collection))
            ->map(static function (ReflectionClass $reflectionClass) {
                return $reflectionClass->getName();
            })
            ->filter(static function ($class) {
                return is_a($class, SecuritySchemeFactory::class, true);
            })
            ->map(static function ($class) {
                /** @var SecuritySchemeFactory $instance */
                $instance = resolve($class);

                return $instance->build();
            })
            ->values()
            ->toArray();
    }
}
