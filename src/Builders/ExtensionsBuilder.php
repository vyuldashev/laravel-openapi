<?php

namespace Vyuldashev\LaravelOpenApi\Builders;

use GoldSpecDigital\ObjectOrientedOAS\Objects\BaseObject;
use Illuminate\Support\Collection;
use Vyuldashev\LaravelOpenApi\Annotations\Extension as ExtensionAnnotation;
use Vyuldashev\LaravelOpenApi\Factories\ExtensionFactory;

class ExtensionsBuilder
{
    public function build(BaseObject $object, Collection $annotations): void
    {
        $annotations
            ->filter(static function ($annotation) {
                return $annotation instanceof ExtensionAnnotation;
            })
            ->each(static function (ExtensionAnnotation $annotation) use ($object) {
                if ($annotation->factory) {
                    /** @var ExtensionFactory $factory */
                    $factory = resolve($annotation->factory);
                    $key = $factory->key();
                    $value = $factory->value();
                } else {
                    $key = $annotation->key;
                    $value = $annotation->value;
                }

                $object->x(
                    $key,
                    $value
                );
            });
    }
}
