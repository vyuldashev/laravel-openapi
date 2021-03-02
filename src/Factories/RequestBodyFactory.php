<?php

namespace Vyuldashev\LaravelOpenApi\Factories;

use Vyuldashev\LaravelOpenApi\Concerns\Referencable;
use Vyuldashev\LaravelOpenApi\Contracts\RequestBodyFactoryInterface;

abstract class RequestBodyFactory implements RequestBodyFactoryInterface
{
    use Referencable;
}
