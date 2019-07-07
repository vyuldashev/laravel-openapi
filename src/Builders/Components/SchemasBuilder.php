<?php

namespace Vyuldashev\LaravelOpenApi\Builders\Components;

use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflector\ClassReflector;
use Roave\BetterReflection\SourceLocator\Type\DirectoriesSourceLocator;
use Vyuldashev\LaravelOpenApi\Factories\SchemaFactory;

class SchemasBuilder
{
    protected static $directories = [];

    public static function in(array $directories): void
    {
        static::$directories = collect($directories)
            ->filter(static function ($directory) {
                return file_exists($directory) && is_dir($directory);
            })
            ->values()
            ->toArray();
    }

    public function build(): array
    {
        $astLocator = (new BetterReflection())->astLocator();
        $reflector = new ClassReflector(
            new DirectoriesSourceLocator(static::$directories, $astLocator)
        );

        return collect($reflector->getAllClasses())
            ->map(static function (ReflectionClass $reflectionClass) {
                return $reflectionClass->getName();
            })
            ->filter(static function ($class) {
                return is_a($class, SchemaFactory::class, true);
            })
            ->map(static function ($class) {
                /** @var SchemaFactory $instance */
                $instance = resolve($class);

                return $instance->build();
            })
            ->values()
            ->toArray();
    }
}
