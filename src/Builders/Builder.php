<?php

namespace Vyuldashev\LaravelOpenApi\Builders;

use GoldSpecDigital\ObjectOrientedOAS\Objects\BaseObject;
use Illuminate\Support\Collection;
use Vyuldashev\LaravelOpenApi\Annotations\Extension as ExtensionAnnotation;
use Vyuldashev\LaravelOpenApi\Factories\ExtensionFactory;

abstract class Builder
{
    /**
     * @param BaseObject $object
     * @param ExtensionAnnotation[]|Collection $annotations
     */
    protected function addExtensions(BaseObject $object, Collection $annotations): void
    {
        $annotations
            ->filter(static function ($annotation) {
                return $annotation instanceof ExtensionAnnotation;
            })
            ->map(static function(ExtensionAnnotation $annotation) use($object) {
                /** @var ExtensionFactory $factory */
                $factory = resolve($annotation->factory);

                $object->x(
                    $factory->key(),
                    $factory->value()
                );
            });
    }
}
