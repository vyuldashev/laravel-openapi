<?php

namespace Vyuldashev\LaravelOpenApi\Builders\Components;

use Illuminate\Support\Collection;
use ReflectionClass;
use Vyuldashev\LaravelOpenApi\Attributes\Collection as CollectionAttribute;
use Vyuldashev\LaravelOpenApi\ClassMapGenerator;
use Vyuldashev\LaravelOpenApi\Generator;

abstract class Builder
{
    protected array $directories = [];

    public function __construct(array $directories)
    {
        $this->directories = $directories;
    }

    protected function getAllClasses(string $collection): Collection
    {
        return collect($this->directories)
            ->map(function (string $directory) {
                $map = ClassMapGenerator::createMap($directory);

                return array_keys($map);
            })
            ->flatten()
            ->filter(function (string $class) use ($collection) {
                $reflectionClass = new ReflectionClass($class);
                $collectionAttributes = $reflectionClass->getAttributes(CollectionAttribute::class);

                if (count($collectionAttributes) === 0 && $collection === Generator::COLLECTION_DEFAULT) {
                    return true;
                }

                if (count($collectionAttributes) === 0) {
                    return false;
                }

                /** @var CollectionAttribute $collectionAttribute */
                $collectionAttribute = $collectionAttributes[0]->newInstance();

                return
                    $collectionAttribute->name === ['*'] ||
                    in_array($collection, $collectionAttribute->name ?? [], true);
            });
    }
}
