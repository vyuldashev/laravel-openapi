<?php

namespace Vyuldashev\LaravelOpenApi\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class ResponseFactoryMakeCommand extends GeneratorCommand
{
    protected $name = 'openapi:make-response';
    protected $description = 'Create a new Response factory class';
    protected $type = 'Response';

    protected function getStub(): string
    {
        return __DIR__.'/stubs/response.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\OpenApi\Responses';
    }

    protected function qualifyClass($name): string
    {
        $name = parent::qualifyClass($name);

        if (Str::endsWith($name, 'Response')) {
            return $name;
        }

        return $name.'Response';
    }
}
