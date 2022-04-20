<?php

namespace Vyuldashev\LaravelOpenApi\Factories;

use Vyuldashev\LaravelOpenApi\Concerns\Referencable;
use Vyuldashev\LaravelOpenApi\Contracts\ResponseFactoryInterface;

abstract class ResponseFactory implements ResponseFactoryInterface
{
    use Referencable;
}
