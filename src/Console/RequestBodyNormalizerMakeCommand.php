<?php

namespace Vyuldashev\LaravelOpenApi\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class RequestBodyNormalizerMakeCommand extends GeneratorCommand
{
    protected $name = 'openapi:make-requestbody-normalizer';
    protected $description = 'Create a new RequestBody normalizer class';
    protected $type = 'RequestBodyNormalizer';

    protected function getStub(): string
    {
        return __DIR__ . '/stubs/requestbody-normalizer.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\OpenApi\RequestBodies';
    }

    protected function qualifyClass($name): string
    {
        $name = parent::qualifyClass($name);

        if (Str::endsWith($name, 'RequestBodyNormalizer')) {
            return $name;
        }

        return $name . 'RequestBodyNormalizer';
    }

    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the RequestBody normalizer'],
        ];
    }
}
