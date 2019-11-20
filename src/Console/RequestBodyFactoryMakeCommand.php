<?php

namespace Vyuldashev\LaravelOpenApi\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class RequestBodyFactoryMakeCommand extends GeneratorCommand
{
    protected $name = 'openapi:make-requestbody';
    protected $description = 'Create a new RequestBody factory class';
    protected $type = 'RequestBody';

    protected function getStub(): string
    {
        return __DIR__.'/stubs/requestbody.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\OpenApi\RequestBodies';
    }

    protected function qualifyClass($name): string
    {
        $name = parent::qualifyClass($name);

        if (Str::endsWith($name, 'RequestBody')) {
            return $name;
        }

        return $name.'RequestBody';
    }
}
