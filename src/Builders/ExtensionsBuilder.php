<?php

namespace Vyuldashev\LaravelOpenApi\Builders;

use GoldSpecDigital\ObjectOrientedOAS\Objects\BaseObject;
use Illuminate\Support\Collection;
use Vyuldashev\LaravelOpenApi\Attributes\Extension as ExtensionAttribute;
use Vyuldashev\LaravelOpenApi\Factories\ExtensionFactory;

class ExtensionsBuilder
{
    public function build(BaseObject $object, Collection $attributes): void
    {
        $attributes
            ->filter(static fn (object $attribute) => $attribute instanceof ExtensionAttribute)
            ->each(static function (ExtensionAttribute $attribute) use ($object): void {
                if ($attribute->factory) {
                    /** @var ExtensionFactory $factory */
                    $factory = app($attribute->factory);
                    $key = $factory->key();
                    $value = $factory->value();
                } else {
                    $key = $attribute->key;
                    $value = $attribute->value;
                }

                $object->x(
                    $key,
                    $value
                );
            });
    }
}
