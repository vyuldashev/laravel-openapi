<?php

namespace Vyuldashev\LaravelOpenApi\Factories;

use Vyuldashev\LaravelOpenApi\Concerns\Referencable;
use Vyuldashev\LaravelOpenApi\Contracts\ParametersFactoryInterface;

abstract class ParametersFactory implements ParametersFactoryInterface
{
    use Referencable;
}
