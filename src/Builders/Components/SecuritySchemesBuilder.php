<?php

namespace Vyuldashev\LaravelOpenApi\Builders\Components;

use Vyuldashev\LaravelOpenApi\Factories\SecuritySchemeFactory;
use Vyuldashev\LaravelOpenApi\Generator;

class SecuritySchemesBuilder extends Builder
{
    protected static $directories = [];

    public function build(string $collection = Generator::COLLECTION_DEFAULT): array
    {
        return $this->getAllClasses($collection)
            ->filter(static function ($class) {
                return is_a($class, SecuritySchemeFactory::class, true);
            })
            ->map(static function ($class) {
                /** @var SecuritySchemeFactory $instance */
                $instance = app($class);

                return $instance->build();
            })
            ->values()
            ->toArray();
    }
}
