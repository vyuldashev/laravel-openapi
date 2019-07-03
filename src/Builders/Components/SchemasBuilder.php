<?php

namespace Vyuldashev\LaravelOpenApi\Builders\Components;

use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Vyuldashev\LaravelOpenApi\Factory\SchemaFactory;

class SchemasBuilder
{
    public function build(string $schemasDirectory): array
    {
        if (!file_exists($schemasDirectory)) {
            return [];
        }

        $namespace = app()->getNamespace();

        $files = (new Finder())->in($schemasDirectory)->files();

        return collect($files)
            ->map(static function (SplFileInfo $file) use ($namespace) {
                $schema = $namespace . str_replace(
                        ['/', '.php'],
                        ['\\', ''],
                        Str::after($file->getPathname(), realpath(app_path()) . DIRECTORY_SEPARATOR)
                    );

                return $schema;
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
