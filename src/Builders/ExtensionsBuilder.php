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
            ->each(static function (ExtensionAnnotation $annotation) use ($object): void {
                if ($annotation->factory) {
                    /** @var ExtensionFactory $factory */
                    $factory = app($annotation->factory);
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
