<?php

namespace Vyuldashev\LaravelOpenApi\Factories;

use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use Vyuldashev\LaravelOpenApi\Concerns\Referencable;

abstract class RequestBodyFactory
{
    use Referencable;

    abstract public function build(): RequestBody;
}
