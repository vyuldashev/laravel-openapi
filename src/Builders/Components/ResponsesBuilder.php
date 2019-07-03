<?php

namespace Vyuldashev\LaravelOpenApi\Builders\Components;

use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Vyuldashev\LaravelOpenApi\Contracts\Reusable;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;
use Vyuldashev\LaravelOpenApi\Factory\SchemaFactory;

class ResponsesBuilder
{
    public function build(string $responsesDirectory): array
    {
        if (!file_exists($responsesDirectory)) {
            return [];
        }

        $namespace = app()->getNamespace();

        $files = (new Finder())->in($responsesDirectory)->files();

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
                return
                    is_a($class, ResponseFactory::class, true) &&
                    is_a($class, Reusable::class, true);
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
