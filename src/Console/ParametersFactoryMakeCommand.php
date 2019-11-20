<?php

namespace Vyuldashev\LaravelOpenApi\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class ParametersFactoryMakeCommand extends GeneratorCommand
{
    protected $name = 'openapi:make-parameters';
    protected $description = 'Create a new Parameters factory class';
    protected $type = 'Parameters';

    protected function getStub(): string
    {
        return __DIR__.'/stubs/parameters.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\OpenApi\Parameters';
    }

    protected function qualifyClass($name): string
    {
        $name = parent::qualifyClass($name);

        if (Str::endsWith($name, 'Parameters')) {
            return $name;
        }

        return $name.'Parameters';
    }
}
