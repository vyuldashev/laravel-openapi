<?php

namespace Vyuldashev\LaravelOpenApi\Builders\Components;

use Doctrine\Common\Annotations\AnnotationReader;
use Illuminate\Support\Collection;
use ReflectionClass as StdReflectionClass;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflector\ClassReflector;
use Roave\BetterReflection\SourceLocator\Type\DirectoriesSourceLocator;
use Vyuldashev\LaravelOpenApi\Annotations\Collection as CollectionAnnotation;
use Vyuldashev\LaravelOpenApi\Generator;

abstract class Builder
{
    public static function in(array $directories): void
    {
        static::$directories = collect($directories)
            ->filter(static function ($directory) {
                return file_exists($directory) && is_dir($directory);
            })
            ->values()
            ->toArray();
    }

    /**
     * @param string $collection
     * @return ReflectionClass[]|Collection
     */
    protected function getAllClasses(string $collection): Collection
    {
        $astLocator = (new BetterReflection())->astLocator();
        $reflector = new ClassReflector(
            new DirectoriesSourceLocator(static::$directories, $astLocator)
        );

        return collect($reflector->getAllClasses())
            ->filter(static function (ReflectionClass $reflectionClass) use ($collection) {
                $reader = new AnnotationReader();
                /** @var CollectionAnnotation|null $collectionAnnotation */
                $collectionAnnotation = $reader->getClassAnnotation(
                    new StdReflectionClass($reflectionClass->getName()),
                    CollectionAnnotation::class
                );

                return
                    ($collectionAnnotation && $collectionAnnotation->name === ['*']) ||
                    (!$collectionAnnotation && $collection === Generator::COLLECTION_DEFAULT) ||
                    ($collectionAnnotation && in_array($collection, $collectionAnnotation->name ?? [], true));
            })
            ->map(static function (ReflectionClass $reflectionClass) {
                return $reflectionClass->getName();
            });
    }
}
