<?php

namespace Vyuldashev\LaravelOpenApi\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class ResponseNormalizerMakeCommand extends GeneratorCommand
{
    protected $name = 'openapi:make-response-normalizer';
    protected $description = 'Create a new Response normalizer class';
    protected $type = 'ResponseNormalizer';

    protected function getStub(): string
    {
        return __DIR__ . '/stubs/response-normalizer.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\OpenApi\Responses';
    }

    protected function qualifyClass($name): string
    {
        $name = parent::qualifyClass($name);

        if (Str::endsWith($name, 'ResponseNormalizer')) {
            return $name;
        }

        return $name . 'ResponseNormalizer';
    }

    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the Response normalizer'],
        ];
    }
}
