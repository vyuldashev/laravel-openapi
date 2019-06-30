<?php

namespace Vyuldashev\LaravelOpenApi\Tests;

use Vyuldashev\LaravelOpenApi\OpenApiServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            OpenApiServiceProvider::class,
        ];
    }
}
