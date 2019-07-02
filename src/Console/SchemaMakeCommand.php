<?php

namespace Vyuldashev\LaravelOpenApi\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class SchemaMakeCommand extends GeneratorCommand
{
    protected $name = 'openapi:make-schema';
    protected $description = 'Create a new Schema class';
    protected $type = 'Schema';

    protected function buildClass($name)
    {
        $output = parent::buildClass($name);

        $output = str_replace('DummySchema', Str::replaceLast('Schema', '', class_basename($name)), $output);

        return $output;
    }

    protected function getStub(): string
    {
        return __DIR__ . '/stubs/schema.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\OpenApi\Schemas';
    }

    protected function qualifyClass($name): string
    {
        $name = parent::qualifyClass($name);

        if (Str::endsWith($name, 'Schema')) {
            return $name;
        }

        return $name . 'Schema';
    }

    protected function getOptions(): array
    {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'The model class schema being generated for'],
        ];
    }
}
