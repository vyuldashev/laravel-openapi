<?php

namespace Vyuldashev\LaravelOpenApi\Builders\Components;

use Vyuldashev\LaravelOpenApi\Contracts\SecuritySchemeFactoryInterface;
use Vyuldashev\LaravelOpenApi\Generator;

class SecuritySchemesBuilder extends Builder
{
    public function build(string $collection = Generator::COLLECTION_DEFAULT): array
    {
        return $this->getAllClasses($collection)
            ->filter(static function ($class) {
                return is_a($class, SecuritySchemeFactoryInterface::class, true);
            })
            ->map(static function ($class) {
                /** @var SecuritySchemeFactoryInterface $instance */
                $instance = app($class);

                return $instance->build();
            })
            ->values()
            ->toArray();
    }
}
