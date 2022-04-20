<?php

namespace Vyuldashev\LaravelOpenApi\Factories;

use Vyuldashev\LaravelOpenApi\Concerns\Referencable;
use Vyuldashev\LaravelOpenApi\Contracts\SchemaFactoryInterface;

abstract class SchemaFactory implements SchemaFactoryInterface
{
    use Referencable;
}
