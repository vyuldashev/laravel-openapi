<?php

namespace Vyuldashev\LaravelOpenApi\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class ParametersNormalizerMakeCommand extends GeneratorCommand
{
    protected $name = 'openapi:make-parameters-normalizer';
    protected $description = 'Create a new Parameters normalizer class';
    protected $type = 'ParametersNormalizer';

    protected function getStub(): string
    {
        return __DIR__ . '/stubs/parameters-normalizer.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\OpenApi\Parameters';
    }

    protected function qualifyClass($name): string
    {
        $name = parent::qualifyClass($name);

        if (Str::endsWith($name, 'ParametersNormalizer')) {
            return $name;
        }

        return $name . 'ParametersNormalizer';
    }

    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the Parameters normalizer'],
        ];
    }
}
