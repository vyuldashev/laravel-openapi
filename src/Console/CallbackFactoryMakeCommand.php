<?php

namespace Vyuldashev\LaravelOpenApi\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class CallbackFactoryMakeCommand extends GeneratorCommand
{
    protected $name = 'openapi:make-callback';
    protected $description = 'Create a new callback factory class';
    protected $type = 'Extension';

    protected function getStub(): string
    {
        return __DIR__.'/stubs/callback.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\OpenApi\Callbacks';
    }

    protected function qualifyClass($name): string
    {
        $name = parent::qualifyClass($name);

        if (Str::endsWith($name, 'Callback')) {
            return $name;
        }

        return $name.'Callback';
    }
}
